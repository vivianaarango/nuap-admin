<?php
namespace App\Http\Controllers\Api;

use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\Contracts\JsonApiResponseInterface;
use App\Libraries\Responders\ErrorObject;
use App\Libraries\Responders\HttpObject;
use App\Libraries\Responders\JsonApiErrorsFormatter;
use App\Models\Payment;
use App\Repositories\Contracts\DbBalanceRepositoryInterface;
use App\Repositories\Contracts\DbBankAccountRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

/**
 * Class RequestPaymentController
 * @package App\Http\Controllers\Api
 */
class RequestPaymentController
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
    protected const BANK_ACCOUNT_NOT_FOUND = 'BANK_ACCOUNT_NOT_FOUND';

    /**
     * @type string
     */
    protected const VALUE_NOT_AVAILABLE = 'VALUE_NOT_AVAILABLE';

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
     * @var DbBankAccountRepositoryInterface
     */
    private $dbBankAccountRepository;

    /**
     * @var DbBalanceRepositoryInterface
     */
    private $dbBalanceRepository;

    /**
     * @var JsonApiErrorsFormatter
     */
    private $jsonErrorFormat;

    /**
     * RequestPaymentController constructor.
     * @param ArrayResponseInterface $arrayResponse
     * @param HttpObject $httpObject
     * @param ErrorObject $errorObject
     * @param JsonApiResponseInterface $jsonApiResponse
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbBankAccountRepositoryInterface $dbBankAccountRepository
     * @param DbBalanceRepositoryInterface $dbBalanceRepository
     * @param JsonApiErrorsFormatter $jsonApiErrorsFormatter
     */
    public function __construct(
        ArrayResponseInterface $arrayResponse,
        HttpObject $httpObject,
        ErrorObject $errorObject,
        JsonApiResponseInterface $jsonApiResponse,
        DbUsersRepositoryInterface $dbUserRepository,
        DbBankAccountRepositoryInterface $dbBankAccountRepository,
        DbBalanceRepositoryInterface $dbBalanceRepository,
        JsonApiErrorsFormatter $jsonApiErrorsFormatter
    ) {
        $this->arrayResponse = $arrayResponse;
        $this->httpObject = $httpObject;
        $this->errorObject = $errorObject;
        $this->jsonApiResponse = $jsonApiResponse;
        $this->dbUserRepository = $dbUserRepository;
        $this->dbBankAccountRepository = $dbBankAccountRepository;
        $this->dbBalanceRepository = $dbBalanceRepository;
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
                'value' => 'required|numeric'
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
                    ->setDetail('No se encontró el usuario.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            $account = $this->dbBankAccountRepository->findByUserID($user->id);
            if (is_null($account)) {
                $error = new ErrorObject();
                $error->setCode(self::BANK_ACCOUNT_NOT_FOUND)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('Necesitas tener una cuenta bancaria asociada a tu cuenta.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            $balance = $this->dbBalanceRepository->findByUserID($user->id);
            if ($request['value'] <= $balance->requested_value) {
                $account = $this->dbBankAccountRepository->findByUserID($user->id);
                $payment['user_id'] = $user->id;
                $payment['user_type'] = $user->role;
                $payment['account_id'] = $account->id;
                $payment['value'] = $request['value'];
                $payment['status'] = Payment::STATUS_PENDING;
                $payment['request_date'] = now();
                Payment::create($payment);

                $balance->requested_value = $balance->requested_value - $payment['value'];
                $balance->save();
            } else {
                $error = new ErrorObject();
                $error->setCode(self::VALUE_NOT_AVAILABLE)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('No cuentas con la cantidad de dinero solicitada.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

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
