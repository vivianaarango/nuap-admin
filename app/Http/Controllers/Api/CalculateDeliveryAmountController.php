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
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Throwable;

/**
 * Class CalculateDeliveryAmountController
 * @package App\Http\Controllers\Api
 */
class CalculateDeliveryAmountController
{
    /**
     * @type string
     */
    protected const PRODUCT_NOT_AVAILABLE = 'PRODUCT_NOT_AVAILABLE';

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
     * CalculateDeliveryAmountController constructor.
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

            $stores = [];
            foreach ($request['products'] as $product) {
                $data = Product::findOrFail($product['id']);
                array_push($stores, $data->user_id);
            }

            $stores = array_unique($stores);

           /* $delivery = 0;
            foreach ($stores as $store) {
                $data = Distributor::where('user_id', $store)
                    ->get();

                if (count($data) == 0) {
                    $data = Commerce::where('user_id', $store)
                        ->get();
                }

                $delivery = $delivery + $data->first()->shipping_cost;
            }*/

            $stores = [];

            foreach ($request['products'] as $product) {
                $data = Product::findOrFail($product['id']);
                array_push($stores, $data->user_id);
            }

            $stores = array_unique($stores);

            $totalAmount = 0;
            $delivery = 0;
            $newDiscount = 0;
            $newTotal = 0;
            foreach ($stores as $store) {
                $dataStore = Distributor::where('user_id', $store)
                    ->get();

                if (count($dataStore) === 0) {
                    $dataStore = Commerce::where('user_id', $store)
                        ->get();
                }

                $total = 0;
                $totalDiscount = 0;
                foreach ($request['products'] as $product) {
                    $data = Product::findOrFail($product['id']);
                    if ($data->user_id === $store) {
                        if ($product['quantity'] <= $data->stock) {
                            if ($data->has_special_price) {
                                $discount = ($data->special_price * $data->sale_price) / 100;

                                $total += ($product['quantity'] * ($data->sale_price - $discount));
                                $totalDiscount += ($product['quantity'] * $discount);
                            } else {
                                $total += ($product['quantity'] * $data->sale_price);
                            }
                            $data->stock -= $product['quantity'];
                            $data->save();
                        } else {
                            $error = new ErrorObject();
                            $error->setCode(self::PRODUCT_NOT_AVAILABLE)
                                ->setTitle(self::ERROR_TITLE)
                                ->setDetail('Producto no disponible.')
                                ->setStatus((string)Response::HTTP_BAD_REQUEST);
                            $this->jsonErrorFormat->add($error);

                            return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
                        }
                    }
                }

                $totalAmount += $total;
                $delivery += $dataStore->first()->shipping_cost;
                $newDiscount += $totalDiscount;
                $newTotal += $total + $dataStore->first()->shipping_cost;
            }

            $this->httpObject->setBody([
                'data' => [
                    'delivery' => '$ ' .$this->formatCurrency($delivery), //13000
                    'total_amount' => '$ ' .$this->formatCurrency($totalAmount),
                    'total' => '$ ' .$this->formatCurrency($newTotal),
                    'discount' => '$ ' .$this->formatCurrency($newDiscount)
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

    /**
     * @param $floatcurr
     * @param string $curr
     * @return string
     */
    public function formatCurrency($floatcurr, $curr = 'COP'): string
    {
        $currencies['COP'] = array(0, ',', '.');
        return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
    }
}
