<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\Contracts\JsonApiResponseInterface;
use App\Libraries\Responders\ErrorObject;
use App\Libraries\Responders\HttpObject;
use App\Libraries\Responders\JsonApiErrorsFormatter;
use App\Models\Order;
use App\Repositories\Contracts\DbOrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Throwable;

/**
 * Class CreateOrderController
 * @package App\Http\Controllers\Api
 */
class PaymentConfirmController
{
    /**
     * @type string
     */
    protected const ERROR_TITLE = 'Error';

    /**
     * @type string
     */
    protected const REFERENCE_NOT_FOUND = 'REFERENCE_NOT_FOUND';

    /**
     * @type string
     */
    protected const INVALID_SIGNATURE = 'INVALID_SIGNATURE';

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
     * @var JsonApiErrorsFormatter
     */
    private $jsonErrorFormat;

    /**
     * @var DbOrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * PaymentConfirmController constructor.
     * @param DbOrderRepositoryInterface $dbOrderRepository
     * @param ArrayResponseInterface $arrayResponse
     * @param HttpObject $httpObject
     * @param ErrorObject $errorObject
     * @param JsonApiResponseInterface $jsonApiResponse
     * @param JsonApiErrorsFormatter $jsonApiErrorsFormatter
     */
    public function __construct(
        DbOrderRepositoryInterface $dbOrderRepository,
        ArrayResponseInterface $arrayResponse,
        HttpObject $httpObject,
        ErrorObject $errorObject,
        JsonApiResponseInterface $jsonApiResponse,
        JsonApiErrorsFormatter $jsonApiErrorsFormatter
    )
    {
        $this->orderRepository = $dbOrderRepository;
        $this->arrayResponse = $arrayResponse;
        $this->httpObject = $httpObject;
        $this->errorObject = $errorObject;
        $this->jsonApiResponse = $jsonApiResponse;
        $this->jsonErrorFormat = $jsonApiErrorsFormatter;
    }

    /**
     * @param Request $request
     * @param int $address
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $address): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'x_ref_payco' => 'required|string',
                'x_transaction_id' => 'required|string',
                'x_amount' => 'required|integer',
                'x_currency_code' => 'required|string',
                'x_signature' => 'required|string',
                'x_response' => 'required|string',
                'x_motivo' => 'required|string',
                'x_cod_response' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->jsonApiResponse->respondFormError(
                    $validator->errors(),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $p_cust_id_client = env('P_CUST_ID_CLIENTE');
            $p_key = env('P_KEY');
            $x_ref_payco = $request['x_ref_payco'];
            $x_transaction_id = $request['x_transaction_id'];
            $x_amount = $request['x_amount'];
            $x_currency_code = $request['x_currency_code'];
            $x_signature = $request['x_signature'];
            $x_cod_response = $request['x_cod_response'];

            $signature = hash(
                'sha256',
                $p_cust_id_client . '^' . $p_key . '^' . $x_ref_payco . '^' . $x_transaction_id . '^' . $x_amount . '^' . $x_currency_code
            );

            if ($signature === $x_signature) {
                $orders = $this->orderRepository->findByReference($x_ref_payco);
                if (! count($orders)){
                    $error = new ErrorObject();
                    $error->setCode(self::REFERENCE_NOT_FOUND)
                        ->setTitle(self::ERROR_TITLE)
                        ->setDetail('No existen ordenes con este número de referencia.')
                        ->setStatus((string) Response::HTTP_BAD_REQUEST);
                    $this->jsonErrorFormat->add($error);

                    return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
                }

                if ($x_cod_response === 1) {
                    foreach ($orders as $order) {
                        $order->status = Order::STATUS_ACCEPTED;
                        $order->save();
                    }
                }
            } else {
                $error = new ErrorObject();
                $error->setCode(self::INVALID_SIGNATURE)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('La firma de la solicitud no es valida.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            $this->httpObject->setBody([
                'data' => null
            ]);

            return $this->arrayResponse->respond($this->httpObject);

        } catch (Throwable $exception) {
            $error = new ErrorObject();
            $error->setCode(self::GENERAL_ERROR)
                ->setTitle(self::ERROR_TITLE)
                ->setDetail('Ha ocurrido un error inesperado')
                ->setStatus((string)Response::HTTP_INTERNAL_SERVER_ERROR);
            $this->jsonErrorFormat->add($error);

            return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
