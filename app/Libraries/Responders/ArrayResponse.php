<?php
namespace App\Libraries\Responders;

use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Serializers\ApiDataSerializer;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\TransformerAbstract;

/**
 * Class ArrayResponse
 * @package App\Libraries\Responders
 * @author Kennit Ruz <kruz@merqueo.com>
 */
class ArrayResponse implements ArrayResponseInterface
{
    /**
     * @var Manager
     */
    private $fractal;

    /**
     * JsonApiResponse constructor.
     * @param Manager $fractal
     */
    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;
    }

    /**
     * @param HttpObject $httpObject
     * @param TransformerAbstract $callback
     * @param string $resource
     * @param array $includes
     * @return JsonResponse
     */
    public function responseWithItem(
        HttpObject $httpObject,
        TransformerAbstract $callback,
        string $resource,
        array $includes = []
    ): JsonResponse {
        $item = $httpObject->getItem();

        $resource = new Item($item, $callback, $resource);

        if (!empty($includes)) {
            $this->fractal->parseIncludes($includes);
        }

        $rootScope = $this->fractal->createData($resource);
        $httpObject->setBody($rootScope->toArray());

        return $this->respond($httpObject);
    }

    /**
     * @param HttpObject $httpObject
     * @param TransformerAbstract $callback
     * @param string $resource
     * @param array $includes
     * @return JsonResponse
     */
    public function respondWithCollection(
        HttpObject $httpObject,
        TransformerAbstract $callback,
        string $resource,
        array $includes = []
    ): JsonResponse {
        // TODO no me está tomando el serializador desde el constructor, temporalmente lo pondré aquí para avanzar.
        $this->fractal->setSerializer(new ArraySerializer());

        $collection = $httpObject->getCollection();

        if (is_array($collection) || $collection instanceof \Illuminate\Support\Collection) {
            $totalItems = count($collection);
            $collection = new LengthAwarePaginator($httpObject->getCollection(), $totalItems, $totalItems ?: 15);
        }

        $resource = new Collection($collection, $callback, $resource);
        $resource->setPaginator(new IlluminatePaginatorAdapter($collection));

        if (! empty($includes)) {
            $this->fractal->parseIncludes($includes);
        }

        $rootScope = $this->fractal->createData($resource);
        $httpObject->setBody($rootScope->toArray());

        return $this->respond($httpObject);
    }

    /**
     * @param HttpObject $httpObject
     * @return JsonResponse
     */
    public function respond(HttpObject $httpObject): JsonResponse
    {
        return response()->json($httpObject->getBody(), $httpObject->getStatus(), $httpObject->getHeaders());
    }
}
