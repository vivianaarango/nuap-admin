<?php
namespace App\Http\Controllers\Api;

use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\Contracts\JsonApiResponseInterface;
use App\Libraries\Responders\ErrorObject;
use App\Libraries\Responders\HttpObject;
use App\Libraries\Responders\JsonApiErrorsFormatter;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use App\Repositories\Contracts\DbTicketRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

/**
 * Class EditUserProfileController
 * @package App\Http\Controllers\Api
 */
class EditUserProfileController
{
    /**
     * @type string
     */
    protected const ERROR_TITLE = 'Error';

    /**
     * @type string
     */
    protected const USER_NOT_FOUND = 'USER_NOT_FOUND';

    /**
     * @type string
     */
    protected const INVALID_PHONE = 'INVALID_PHONE';

    /**
     * @type string
     */
    protected const INVALID_EMAIL = 'INVALID_EMAIL';

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
     * @var DbClientRepositoryInterface
     */
    private $dbClientRepository;

    /**
     * @var JsonApiErrorsFormatter
     */
    private $jsonErrorFormat;

    /**
     * EditUserProfileController constructor.
     * @param ArrayResponseInterface $arrayResponse
     * @param HttpObject $httpObject
     * @param ErrorObject $errorObject
     * @param JsonApiResponseInterface $jsonApiResponse
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbClientRepositoryInterface $dbClientRepository
     * @param JsonApiErrorsFormatter $jsonApiErrorsFormatter
     */
    public function __construct(
        ArrayResponseInterface $arrayResponse,
        HttpObject $httpObject,
        ErrorObject $errorObject,
        JsonApiResponseInterface $jsonApiResponse,
        DbUsersRepositoryInterface $dbUserRepository,
        DbClientRepositoryInterface $dbClientRepository,
        JsonApiErrorsFormatter $jsonApiErrorsFormatter
    ) {
        $this->arrayResponse = $arrayResponse;
        $this->httpObject = $httpObject;
        $this->errorObject = $errorObject;
        $this->jsonApiResponse = $jsonApiResponse;
        $this->dbUserRepository = $dbUserRepository;
        $this->dbClientRepository = $dbClientRepository;
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
                'name' => 'required|string',
                'email' => 'required|string',
                'phone' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->jsonApiResponse->respondFormError(
                    $validator->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $token = $request->header('Authorization');
            $token = explode(' ',$token)[1];

            $user = $this->dbUserRepository->getUserByToken($token);

            if (is_null($user)) {
                $error = new ErrorObject();
                $error->setCode(self::USER_NOT_FOUND)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('No se encontroó el usuario.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            if ($user->email != $request->input('email')){
                $userEdit = $this->dbUserRepository->getUserByEmail($request->input('email'));

                if (is_null($userEdit)) {
                    $user->email = $request->input('email');
                } else {
                    $error = new ErrorObject();
                    $error->setCode(self::INVALID_EMAIL)
                        ->setTitle(self::ERROR_TITLE)
                        ->setDetail('Este email ya está en uso.')
                        ->setStatus((string) Response::HTTP_BAD_REQUEST);
                    $this->jsonErrorFormat->add($error);

                    return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
                }
            }

            if ($user->phone != $request->input('phone')){
                $userEdit = $this->dbUserRepository->getUserByPhone($request->input('phone'));

                if (is_null($userEdit)) {
                    $user->phone = $request->input('phone');
                } else {
                    $error = new ErrorObject();
                    $error->setCode(self::INVALID_PHONE)
                        ->setTitle(self::ERROR_TITLE)
                        ->setDetail('Este teléfono ya está en uso.')
                        ->setStatus((string) Response::HTTP_BAD_REQUEST);
                    $this->jsonErrorFormat->add($error);

                    return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
                }
            }

            $user->save();

            $client = $this->dbClientRepository->findByUserID($user->id);
            $client->name = $request->input('name');
            $client->save();

            $this->httpObject->setBody([
                'data' => null
            ]);

            return $this->arrayResponse->respond($this->httpObject);

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
