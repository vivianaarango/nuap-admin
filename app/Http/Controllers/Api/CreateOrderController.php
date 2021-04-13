<?php
namespace App\Http\Controllers\Api;

use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\Contracts\JsonApiResponseInterface;
use App\Libraries\Responders\ErrorObject;
use App\Libraries\Responders\HttpObject;
use App\Libraries\Responders\JsonApiErrorsFormatter;
use App\Models\Balance;
use App\Models\Commerce;
use App\Models\Distributor;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Throwable;

/**
 * Class CreateOrderController
 * @package App\Http\Controllers\Api
 */
class CreateOrderController
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
    protected const PRODUCT_NOT_AVAILABLE = 'PRODUCT_NOT_AVAILABLE';

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
     * CreateOrderController constructor.
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
     * @param int $address
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $address): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'products' => 'required|array',
                'products.*.id' => 'required|integer',
                'products.*.quantity' => 'required|integer'
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

            $address = $this->dbUserRepository->findByUserLocationID($address);

            if (is_null($address)) {
                $error = new ErrorObject();
                $error->setCode(self::ADDRESS_NOT_FOUND)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('No se encontró la dirección enviada.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            $stores = [];

            foreach ($request['products'] as $product) {
                $data = Product::findOrFail($product['id']);
                array_push($stores, $data->user_id);
            }

            $stores = array_unique($stores);

            $orders = [];
            foreach ($stores as $store) {
                $userType = 'Distribuidor';
                $dataStore = Distributor::where('user_id', $store)
                    ->get();

                if (count($dataStore) == 0) {
                    $userType = "Comercio";
                    $dataStore = Commerce::where('user_id', $store)
                        ->get();
                }

                $total = 0;
                $totalDiscount = 0;
                $countProducts = 0;
                foreach ($request['products'] as $product) {
                    $data = Product::findOrFail($product['id']);
                    if ($data->user_id == $store) {
                        if ($product['quantity'] <= $data->stock) {
                            $countProducts += $product['quantity'];
                            if ($data->has_special_price) {;
                                $discount = ($data->special_price *  $data->sale_price) / 100;
                                $total = $total + ($product['quantity'] * ($data->sale_price - $discount));
                                $totalDiscount = $totalDiscount + ($product['quantity'] * $discount);
                            } else {
                                $total = $total + ($product['quantity'] * $data->sale_price);
                            }
                            $data->stock = $data->stock - $product['quantity'];
                            $data->save();
                        } else {
                            $error = new ErrorObject();
                            $error->setCode(self::PRODUCT_NOT_AVAILABLE)
                                ->setTitle(self::ERROR_TITLE)
                                ->setDetail('Producto no disponible.')
                                ->setStatus((string) Response::HTTP_BAD_REQUEST);
                            $this->jsonErrorFormat->add($error);

                            return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
                        }
                    }
                }

                $order = new Order();
                $order->user_id = $store;
                $order->user_type = $userType;
                $order->status = Order::STATUS_INITIATED;
                $order->client_id = $user->id;
                $order->client_type = $user->role;
                $order->total_products = $countProducts;
                $order->total_amount = $total;
                $order->delivery_amount = $dataStore->first()->shipping_cost;
                $order->total_discount = $totalDiscount;
                $order->total = $total + $dataStore->first()->shipping_cost;
                $order->address_id = $address->id;
                $order->save();

                foreach ($request['products'] as $product) {
                    $data = Product::findOrFail($product['id']);
                    if ($data->user_id == $store) {
                        $orderProduct = new OrderProduct();
                        $orderProduct->product_id = $data->id;
                        $orderProduct->order_id = $order->id;
                        $orderProduct->quantity = $product['quantity'];

                        if ($data->has_special_price) {
                            $discount = ($data->special_price *  $data->sale_price) / 100;
                            $orderProduct->price = $data->sale_price - $discount;
                        } else {
                            $orderProduct->price = $data->sale_price;
                        }
                        $orderProduct->save();
                    }
                }
                array_push($orders, $order->id);

                $balance = Balance::where('user_id', $dataStore->first()->id)->get();
                $totalWithoutCommission = $total - (($total * $dataStore->first()->commission) / 100);
                if (! count($balance)) {
                    $balance = new Balance();
                    $balance->user_id = $dataStore->first()->id;
                    $balance->user_type = $userType;
                    $balance->balance = $totalWithoutCommission;
                    $balance->paid_out = 0;
                    $balance->total = $totalWithoutCommission;
                    $balance->requested_value = 0;
                } else {
                    $balance = Balance::findOrFail($balance->first()->id);
                    $balance->balance += $totalWithoutCommission;
                    $balance->total += $totalWithoutCommission;
                }
                $balance->save();
            }

            $this->httpObject->setBody([
                'data' => [
                    'orders' => $orders
                ]
            ]);

            return $this->arrayResponse->respond($this->httpObject);

        } catch (Throwable $exception) {
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
