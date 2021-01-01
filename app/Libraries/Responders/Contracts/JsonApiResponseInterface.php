<?php


namespace App\Libraries\Responders\Contracts;

use App\Libraries\Responders\HttpObject;
use App\Libraries\Responders\JsonApiErrorsFormatter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;
use League\Fractal\TransformerAbstract;

/**
 * Interface JsonApiResponseInterface
 * @package App\Libraries\Responders\Contracts
 */
interface JsonApiResponseInterface
{
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
     * @param $callback
     * @param string $resource
     * @return JsonResponse
     */
    public function respondWithCallback(HttpObject $httpObject, $callback, string $resource): JsonResponse;

    /**
     * @param HttpObject $httpObject
     * @return JsonResponse
     */
    public function respond(HttpObject $httpObject): JsonResponse;

    /**
     * @param JsonApiErrorsFormatter $errors
     * @param int|string $status
     * @param array $headers
     * @return JsonResponse
     */
    public function respondError(JsonApiErrorsFormatter $errors, int $status, array $headers = []): JsonResponse;

    /**
     * @param MessageBag $messageBag
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    public function respondFormError(MessageBag $messageBag, int $status, array $headers = []): JsonResponse;

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
