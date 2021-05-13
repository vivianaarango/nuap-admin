<?php
namespace App\Http\Controllers\Api;

use App\Http\Transformers\LocationTransformer;
use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\Contracts\JsonApiResponseInterface;
use App\Libraries\Responders\ErrorObject;
use App\Libraries\Responders\HttpObject;
use App\Libraries\Responders\JsonApiErrorsFormatter;
use App\Mail\SendEmail;
use App\Models\Commerce;
use App\Models\Distributor;
use App\Models\Product;
use App\Models\User;
use App\Models\UserLocation;
use App\Repositories\Contracts\DbProductRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

/**
 * Class RegisterClientController
 * @package App\Http\Controllers\Api
 */
class CreateProductCommerceController
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
     * @var DbProductRepositoryInterface
     */
    private $dbProductRepository;

    /**
     * @var JsonApiErrorsFormatter
     */
    private $jsonErrorFormat;

    /**
     * CreateProductCommerceController constructor.
     * @param ArrayResponseInterface $arrayResponse
     * @param HttpObject $httpObject
     * @param ErrorObject $errorObject
     * @param JsonApiResponseInterface $jsonApiResponse
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param JsonApiErrorsFormatter $jsonApiErrorsFormatter
     * @param DbProductRepositoryInterface $dbProductRepository
     */
    public function __construct(
        ArrayResponseInterface $arrayResponse,
        HttpObject $httpObject,
        ErrorObject $errorObject,
        JsonApiResponseInterface $jsonApiResponse,
        DbUsersRepositoryInterface $dbUserRepository,
        JsonApiErrorsFormatter $jsonApiErrorsFormatter,
        DbProductRepositoryInterface $dbProductRepository
    ) {
        $this->arrayResponse = $arrayResponse;
        $this->httpObject = $httpObject;
        $this->errorObject = $errorObject;
        $this->jsonApiResponse = $jsonApiResponse;
        $this->dbUserRepository = $dbUserRepository;
        $this->jsonErrorFormat = $jsonApiErrorsFormatter;
        $this->dbProductRepository = $dbProductRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|numeric',
                'name' => 'required|string',
                'sku' => 'required|string',
                'brand' => 'required|string',
                'description' => 'required|string',
                'is_featured' => 'required|bool',
                'stock' => 'required|numeric',
                'weight' => 'required|numeric',
                'length' => 'required|numeric',
                'height' => 'required|numeric',
                'width' => 'required|numeric',
                'purchase_price' => 'required|string',
                'sale_price' => 'required|string',
                'special_price' => 'required|string',
                'has_special_price' => 'required|bool'
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

            if (is_null($request->input('product_id'))) {
                $data = [];
                $data['status'] = Product::STATUS_INACTIVE;
                $data['position'] = 0;
                $data['user_id'] =  $user->id;
                $data['category_id'] = $request->input('category_id');
                $data['name'] = $request->input('name');
                $data['sku'] = $request->input('sku');
                $data['brand'] = $request->input('brand');
                $data['description'] = $request->input('description');
                $data['stock'] = $request->input('stock');
                $data['weight'] = $request->input('weight');
                $data['length'] = $request->input('length');
                $data['width'] = $request->input('width');
                $data['height'] = $request->input('height');
                $data['purchase_price'] = $request->input('purchase_price');
                $data['sale_price'] = $request->input('sale_price');
                $data['special_price'] = $request->input('special_price');
                $data['is_featured'] = $request->input('is_featured');
                $data['has_special_price'] = $request->input('has_special_price');
                $product = Product::create($data);

                //send email
                $admin = User::where('role', User::ADMIN_ROLE)->get();
                $emails = [];
                foreach ($admin as $item) {
                    array_push($emails, $item->email);
                }

                $commerce = Commerce::where('user_id', $user->id)->first();
                Mail::to($emails)->send(new SendEmail(
                        '',
                        sprintf("%s ha creado un nuevo producto con el id %d, por favor revisalo lo antes
                                posible para gestionar su aprobación. Click en el siguiente enlace para ver el producto 
                                https://thenuap.com/admin/product-list",
                            $commerce->business_name, $product->id),
                        '¡Han creado un nuevo producto!.'
                    )
                );
            } else {
                $product = $this->dbProductRepository->findByID($request->input('product_id'));
                $product->status = Product::STATUS_INACTIVE;
                $product->position = 0;
                $product->user_id =  $user->id;
                $product->category_id = $request->input('category_id');
                $product->name = $request->input('name');
                $product->sku = $request->input('sku');
                $product->brand = $request->input('brand');
                $product->description = $request->input('description');
                $product->stock = $request->input('stock');
                $product->weight = $request->input('weight');
                $product->length = $request->input('length');
                $product->width = $request->input('width');
                $product->height = $request->input('height');
                $product->purchase_price = $request->input('purchase_price');
                $product->sale_price = $request->input('sale_price');
                $product->special_price = $request->input('special_price');
                $product->is_featured = $request->input('is_featured');
                $product->has_special_price = $request->input('has_special_price');
                $product->save();
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
