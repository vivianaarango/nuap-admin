<?php


namespace App\Libraries\Responders;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use stdClass;

/**
 * Class HttpObject
 * @package App\Libraries\Responders
 */
class HttpObject
{

    /**
     * @var array
     */
    private $body = [];

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var int
     */
    private $status = 200;


    /**
     * @var array|\Illuminate\Support\Collection|Collection $collection
     */
    private $collection;

    /**
     * @var array|Model|Item
     */
    private $item;

    /**
     * @return array|Model|Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param array|Model|StdClass|Item $item
     * @return HttpObject
     */
    public function setItem($item): HttpObject
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return array|\Illuminate\Support\Collection|Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param array|\Illuminate\Support\Collection|Collection|\Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\LengthAwarePaginator $collection
     * @return HttpObject
     */
    public function setCollection($collection): HttpObject
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @param array $body
     * @return HttpObject
     */
    public function setBody(array $body): HttpObject
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return HttpObject
     */
    public function setHeaders(array $headers): HttpObject
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return HttpObject
     */
    public function setStatus(int $status): HttpObject
    {
        $this->status = $status;
        return $this;
    }
}
