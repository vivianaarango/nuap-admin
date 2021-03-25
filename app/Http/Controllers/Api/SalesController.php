<?php
namespace App\Http\Controllers\Api;

use App\Http\Transformers\ProductsTransformer;
use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\Contracts\JsonApiResponseInterface;
use App\Libraries\Responders\ErrorObject;
use App\Libraries\Responders\HttpObject;
use App\Libraries\Responders\JsonApiErrorsFormatter;
use App\Models\User;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use App\Repositories\Contracts\DbProductRepositoryInterface;
use App\Repositories\Contracts\DbUsersRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Exception;

/**
 * Class SalesController
 * @package App\Http\Controllers\Api
 */
class SalesController
{
    /**
     * @type string
     */
    protected const ERROR_TITLE = 'Error';

    /**
     * @type string
     */
    protected const PRODUCTS_NOT_FOUND = 'PRODUCTS_NOT_FOUND';

    /**
     * @type string
     */
    protected const USER_NOT_FOUND = 'USER_NOT_FOUND';

    /**
     * @type string
     */
    protected const ADDRESS_NOT_FOUND = 'ADDRESS_NOT_FOUND';

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
     * @var DbDistributorRepositoryInterface
     */
    private $dbDistributorRepository;

    /**
     * @var DbCommerceRepositoryInterface
     */
    private $dbCommerceRepository;

    /**
     * @var JsonApiErrorsFormatter
     */
    private $jsonErrorFormat;

    /**
     * SalesController constructor.
     * @param ArrayResponseInterface $arrayResponse
     * @param HttpObject $httpObject
     * @param ErrorObject $errorObject
     * @param JsonApiResponseInterface $jsonApiResponse
     * @param DbUsersRepositoryInterface $dbUserRepository
     * @param DbProductRepositoryInterface $dbProductRepository
     * @param JsonApiErrorsFormatter $jsonApiErrorsFormatter
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     */
    public function __construct(
        ArrayResponseInterface $arrayResponse,
        HttpObject $httpObject,
        ErrorObject $errorObject,
        JsonApiResponseInterface $jsonApiResponse,
        DbUsersRepositoryInterface $dbUserRepository,
        DbProductRepositoryInterface $dbProductRepository,
        JsonApiErrorsFormatter $jsonApiErrorsFormatter,
        DbDistributorRepositoryInterface $dbDistributorRepository,
        DbCommerceRepositoryInterface $dbCommerceRepository
    ) {
        $this->arrayResponse = $arrayResponse;
        $this->httpObject = $httpObject;
        $this->errorObject = $errorObject;
        $this->jsonApiResponse = $jsonApiResponse;
        $this->dbUserRepository = $dbUserRepository;
        $this->dbProductRepository = $dbProductRepository;
        $this->jsonErrorFormat = $jsonApiErrorsFormatter;
        $this->dbCommerceRepository = $dbCommerceRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
    }

    /**
     * @param Request $request
     * @param int $address
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $address): JsonResponse
    {
        try {
            $token = $request->header('Authorization');
            $token = explode(' ',$token)[1];

            $user = $this->dbUserRepository->getUserByToken($token);

            if (is_null($user)) {
                $error = new ErrorObject();
                $error->setCode(self::USER_NOT_FOUND)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('No se encontr贸 el usuario.')
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

            if ($user->role === User::COMMERCE_ROLE) {
                $stores = $this->dbDistributorRepository->findValidDistributorsToAddProducts();
            } else {
                $stores = $this->dbCommerceRepository->findValidCommercesToAddProducts();
            }

            $storeIDS = [];
            foreach ($stores as $store) {
                $locations = $this->dbUserRepository->getLocationsByUser($store->user_id);
                $addStore = true;

                if (count($locations) === 0) {
                    $addStore = false;
                }

                foreach ($locations as $location) {
                    $validate = $this->validateDistance(
                        $store->distance,
                        $location->latitude,
                        $location->longitude,
                        $address->latitude,
                        $address->longitude
                    );

                    if (! $validate){
                        $addStore = false;
                    } else {
                        break;
                    }
                }
                if ($addStore) {
                    array_push($storeIDS, $store->user_id);
                }
            }

            $products = $this->dbProductRepository->getSalesByUserID($storeIDS);
            if (!count($products)) {
                $error = new ErrorObject();
                $error->setCode(self::PRODUCTS_NOT_FOUND)
                    ->setTitle(self::ERROR_TITLE)
                    ->setDetail('No se encontraron productos.')
                    ->setStatus((string) Response::HTTP_BAD_REQUEST);
                $this->jsonErrorFormat->add($error);

                return $this->jsonApiResponse->respondError($this->jsonErrorFormat, Response::HTTP_BAD_REQUEST);
            }

            $this->httpObject->setCollection($products);

            return $this->arrayResponse->respondWithCollection(
                $this->httpObject,
                new ProductsTransformer(),
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

    /**
     * @param $distance
     * @param $latStore
     * @param $longStore
     * @param $latClient
     * @param $longClient
     * @return bool
     */
    private function validateDistance($distance, $latStore, $longStore, $latClient, $longClient): bool
    {
        //Distancia en kilometros en 1 grado distancia.
        $km = 111.302;
        //1 Grado = 0.01745329 Radianes
        $degtorad = 0.01745329;
        //1 Radian = 57.29577951 Grados
        $radtodeg = 57.29577951;
        //La formula que calcula la distancia en grados en una esfera, llamada formula de Harvestine.
        $dlong = ($longStore - $longClient);
        $dvalue = (sin($latStore * $degtorad) * sin($latClient * $degtorad)) + (cos($latStore * $degtorad)
                * cos($latClient * $degtorad) * cos($dlong * $degtorad));
        $dd = acos($dvalue) * $radtodeg;
        $distancia = round(($dd * $km), 2);
        //dd($lat_vehi.",".$long_vehi." - ".$distancia." - ".$radio."|".$lat_geo.",".$long_geo);
        if ($distancia <= $distance) {
            return true;
        } else {
            return false;
        }
    }
}