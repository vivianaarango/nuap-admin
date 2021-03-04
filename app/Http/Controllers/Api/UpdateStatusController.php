<?php
namespace App\Http\Controllers\Api;

use App\Http\Transformers\OrdersTransformer;
use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\Contracts\JsonApiResponseInterface;
use App\Libraries\Responders\ErrorObject;
use App\Libraries\Responders\HttpObject;
use App\Libraries\Responders\JsonApiErrorsFormatter;
use App\Models\Order;
use App\Repositories\Contracts\DbClientRepositoryInterface;
use App\Repositories\Contracts\DbOrderRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Exception;

/**
 * Class UpdateStatusController
 * @package App\Http\Controllers\Api
 */
class UpdateStatusController
{
    /**
     * @type string
     */
    protected const ERROR_TITLE = 'Error';

    /**
     * @type string
     */
    protected const UPDATE_ORDER_ERROR = 'UPDATE_ORDER_ERROR';

    /**
     * @type string
     */
    protected const USER_NOT_FOUND = 'USER_NOT_FOUND';

    /**
     * @type string
     */
    protected const ORDER_NOT_FOUND = 'ORDER_NOT_FOUND';

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
     * @var DbOrderRepositoryInterface
     */
    private $dbOrderRepository;

    /**
     * @var JsonApiErrorsFormatter
     */
    private $jsonErrorFormat;

    /**
     * UpdateStatusController constructor.
     * @param ArrayResponseInterface $arrayResponse
     * @param HttpObject $httpObject
     * @param ErrorObject $errorObject
     * @param JsonApiResponseInterface $jsonApiResponse
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbOrderRepositoryInterface $dbOrderRepository
     * @param JsonApiErrorsFormatter $jsonApiErrorsFormatter
     */
    public function __construct(
        ArrayResponseInterface $arrayResponse,
        HttpObject $httpObject,
        ErrorObject $errorObject,
        JsonApiResponseInterface $jsonApiResponse,
        DbUsersRepositoryInterface $dbUserRepository,
        DbOrderRepositoryInterface $dbOrderRepository,
        JsonApiErrorsFormatter $jsonApiErrorsFormatter
    ) {
        $this->arrayResponse = $arrayResponse;
        $this->httpObject = $httpObject;
        $this->errorObject = $errorObject;
        $this->jsonApiResponse = $jsonApiResponse;
        $this->dbUserRepository = $dbUserRepository;
        $this->dbOrderRepository = $dbOrderRepository;
        $this->jsonErrorFormat = $jsonApiErrorsFormatter;
    }

    /**
     * @param Request $request
     * @param int $orderID
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $orderID): JsonResponse
    {
        try {
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

            $order = $this->dbOrderRepository->findByID($orderID);

            if (is_null($order)) {
                $error = new ErrorObject();
                $error->setCode(self::ORDER_NOT_FOUND)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('No se encontro la orden.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            if ($order->status === Order::STATUS_INITIATED) {
                $error = new ErrorObject();
                $error->setCode(self::ORDER_NOT_FOUND)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('Aún no se ha procesado el pago de este pedido.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            if ($order->status === Order::STATUS_DELIVERED) {
                $error = new ErrorObject();
                $error->setCode(self::ORDER_NOT_FOUND)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('Este pedido ya se encuentra entregado.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            if ($order->status === Order::STATUS_ACCEPTED) {
                $order->status = Order::STATUS_ENLISTMENT;
            } else if ($order->status === Order::STATUS_ENLISTMENT) {
                $order->status = Order::STATUS_CIRCULATION;
            } else if ($order->status === Order::STATUS_CIRCULATION) {
                $order->status = Order::STATUS_DELIVERED;
            }

            $order->save();

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
