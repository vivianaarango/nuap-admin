<?php
namespace App\Http\Controllers\Api;

use App\Http\Transformers\LoginTransformer;
use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\Contracts\JsonApiResponseInterface;
use App\Libraries\Responders\ErrorObject;
use App\Libraries\Responders\HttpObject;
use App\Libraries\Responders\JsonApiErrorsFormatter;
use App\Models\SessionLog;
use App\Models\User;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

/**
 * Class LoginController
 * @package App\Http\Controllers\Api
 */
class LoginController
{
    /**
     * @type string
     */
    protected const ERROR_TITLE = 'Error';

    /**
     * @type string
     */
    protected const USER_NOT_ACTIVE = 'USER_NOT_ACTIVE';

    /**
     * @type string
     */
    protected const USER_NOT_FOUND = 'USER_NOT_FOUND';

    /**
     * @type string
     */
    protected const GENERAL_ERROR = 'GENERAL_ERROR';

    /**
     * @var HttpObject
     */
    private $httpObject;

    /**
     * @var ErrorObject
     */
    private $errorObject;

    /**
     * @var ArrayResponseInterface
     */
    private $arrayResponse;

    /**
     * @var JsonApiResponseInterface
     */
    private $jsonApiResponse;

    /**
     * @var DbUsersRepositoryInterface
     */
    private $dbUserRepository;

    /**
     * @var JsonApiErrorsFormatter
     */
    private $jsonErrorFormat;

    /**
     * LoginController constructor.
     * @param ArrayResponseInterface $arrayResponse
     * @param HttpObject $httpObject
     * @param ErrorObject $errorObject
     * @param JsonApiResponseInterface $jsonApiResponse
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param JsonApiErrorsFormatter $jsonApiErrorsFormatter
     */
    public function __construct(
        ArrayResponseInterface $arrayResponse,
        HttpObject $httpObject,
        ErrorObject $errorObject,
        JsonApiResponseInterface $jsonApiResponse,
        DbUsersRepositoryInterface $dbUserRepository,
        JsonApiErrorsFormatter $jsonApiErrorsFormatter
    ) {
        $this->arrayResponse = $arrayResponse;
        $this->httpObject = $httpObject;
        $this->errorObject = $errorObject;
        $this->jsonApiResponse = $jsonApiResponse;
        $this->dbUserRepository = $dbUserRepository;
        $this->jsonErrorFormat = $jsonApiErrorsFormatter;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
                'type' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->jsonApiResponse->respondFormError($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $password = md5($request->input('password'));

            if (strtolower($request->input('type')) === "comercio") {
                $type = User::COMMERCE_ROLE;
            } else {
                $type = User::USER_ROLE;
            }

            $user = $this->dbUserRepository->clientOrCommerceByEmailAndPassword(
                $request->input('email'),
                $password,
                $type
            )->first();

            if (is_null($user)) {
                $error = new ErrorObject();
                $error->setCode(self::USER_NOT_FOUND)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('Verifique su correo electrónico y contraseña.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }


            if ($user->status == User::STATUS_INACTIVE) {
                $error = new ErrorObject();
                $error->setCode(self::USER_NOT_ACTIVE)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('Su usuario no se encuentra activado.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            $logSession = new SessionLog();
            $logSession->user_id = $user->id;
            $logSession->user_type = $user->role;
            $logSession->login_date = now();
            $logSession->save();

            $this->httpObject->setItem($user);

            return $this->arrayResponse->responseWithItem(
                $this->httpObject,
                new LoginTransformer(),
                'data'
            );
        } catch (Exception $exception) {
            $error = new ErrorObject();
            $error->setCode(self::GENERAL_ERROR)
                ->setTitle(self::ERROR_TITLE)
                ->setDetail('Ha ocurrido un error inesperado')
                ->setStatus((string) Response::HTTP_INTERNAL_SERVER_ERROR);
            $this->jsonErrorFormat->add($error);

            return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
