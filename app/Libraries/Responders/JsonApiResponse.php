<?php
namespace App\Libraries\Responders;

use League\Fractal\Manager;
use Illuminate\Http\JsonResponse;
use League\Fractal\Resource\Item;
use Illuminate\Support\MessageBag;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Libraries\Responders\Contracts\JsonApiResponseInterface;

/**
 * Class JsonApiResponse
 * @package App\Libraries\Responders
 */
class JsonApiResponse implements JsonApiResponseInterface
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
        $this->fractal->setSerializer(new JsonApiSerializer());
    }

    /**
     * @param  HttpObject          $httpObject
     * @param  TransformerAbstract $callback
     * @param  string              $resource
     * @param  array               $includes
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

        if (! empty($includes)) {
            $this->fractal->parseIncludes($includes);
        }

        $rootScope = $this->fractal->createData($resource);
        $httpObject->setBody($rootScope->toArray());

        return $this->respond($httpObject);
    }

    /**
     * @param  HttpObject          $httpObject
     * @param  TransformerAbstract $callback
     * @param  string              $resource
     * @param  array               $includes
     * @return JsonResponse
     */
    public function respondWithCollection(
        HttpObject $httpObject,
        TransformerAbstract $callback,
        string $resource,
        array $includes = []
    ): JsonResponse {
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
     * @param  HttpObject     $httpObject
     * @return JsonResponse
     */
    public function respond(HttpObject $httpObject): JsonResponse
    {
        return response()->json($httpObject->getBody(), $httpObject->getStatus(), $httpObject->getHeaders());
    }

    /**
     * @param  MessageBag     $messageBag
     * @param  int            $status
     * @param  array          $headers
     * @return JsonResponse
     */
    public function respondFormError(MessageBag $messageBag, int $status, array $headers = []): JsonResponse
    {
        $errors = new JsonApiErrorsFormatter();

        foreach ($messageBag->getMessages() as $field => $messages) {
            foreach ($messages as $message) {
                $error = new ErrorObject();
                $error->setStatus((string) $status)
                    ->setTitle("Error in Field")
                    ->setDetail($message)
                    ->setCode("FORM_ERROR")
                    ->setSource([
                        'parameter' => $field,
                    ]);

                $errors->add($error);
            }
        }

        return $this->respondError($errors, $status, $headers);
    }

    /**
     * @param  JsonApiErrorsFormatter $errors
     * @param  int                    $status
     * @param  array                  $headers
     * @return JsonResponse
     */
    public function respondError(JsonApiErrorsFormatter $errors, int $status, array $headers = []): JsonResponse
    {
        return response()->json($errors->get(), $status, $headers);
    }

    /**
     * @param  HttpObject     $httpObject
     * @param  $callback
     * @param  string         $resource
     * @return JsonResponse
     */
    public function respondWithCallback(HttpObject $httpObject, $callback, string $resource): JsonResponse
    {
        $item = $httpObject->getItem();

        $resource = new Item($item, $callback, $resource);

        $rootScope = $this->fractal->createData($resource);

        $httpObject->setBody($rootScope->toArray());

        return $this->respond($httpObject);
    }
}
