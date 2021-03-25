<?php
namespace App\Libraries\Responders\Contracts;

use App\Libraries\Responders\HttpObject;
use Illuminate\Http\JsonResponse;
use League\Fractal\TransformerAbstract;

/**
 * Interface ArrayResponseInterface
 * @package App\Libraries\Responders\Contracts
 * @author Kennit Ruz <kruz@merqueo.com>
 */
interface ArrayResponseInterface
{
    /**
     * @param HttpObject $httpObject
     * @return JsonResponse
     */
    public function respond(HttpObject $httpObject): JsonResponse;

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
    ): JsonResponse;

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
    ): JsonResponse;
}
