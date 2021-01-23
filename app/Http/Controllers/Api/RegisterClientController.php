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
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Exception;

/**
 * Class RegisterClientController
 * @package App\Http\Controllers\Api
 */
class RegisterClientController
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
     * RegisterClientController constructor.
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
                'email' => 'required|email|unique:users',
                'phone' => 'required|string|unique:users',
                'password' => 'required|string',
                'name' => 'required|string',
                'last_name' => 'required|string',
                'identity_number' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->jsonApiResponse->respondFormError($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $password = md5($request->input('password'));

            $data['phone'] = $request->input('phone');
            $data['email'] = $request->input('email');
            $data['name'] = $request->input('name');
            $data['last_name'] = $request->input('last_name');
            $data['identity_number'] = $request->input('identity_number');
            $data['role'] = User::USER_ROLE;
            $data['status'] = User::STATUS_ACTIVE;
            $data['last_logged_in'] = now();
            $data['password'] = $password;
            $data['phone_validated'] = User::PHONE_NOT_VALIDATED;
            $data['api_token'] = Str::random(60);

            $user = User::create($data);

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
