<?php


namespace App\Libraries\Responders;

/**
 * Class ErrorObject
 * @package App\Libraries\Responders
 */
class ErrorObject
{

    /**
     * a unique identifier for this particular occurrence of the problem
     * @var string
     */
    private $id = '';

    /**
     * a links object containing the following members
     * @var array
     */
    private $links = [];

    /**
     * the HTTP status code applicable to this problem, expressed as a string value.
     * @var string
     */
    private $status = '';

    /**
     * an application-specific error code, expressed as a string value
     * @var string
     */
    private $code = '';

    /**
     *  a short, human-readable summary of the problem that SHOULD NOT change from occurrence to occurrence of the problem, except for purposes of localization.
     * @var string
     */
    private $title = '';

    /**
     *  a human-readable explanation specific to this occurrence of the problem. Like title, this field’s value can be localized.
     * @var string
     */
    private $detail = '';

    /**
     * an object containing references to the source of the error, optionally including any of the following members
     * - pointer: a JSON Pointer [RFC6901] to the associated entity in the request document [e.g. "/data" for a primary data object, or "/data/attributes/title" for a specific attribute].
     * - parameter: a string indicating which URI query parameter caused the error.
     * @var array
     */
    private $source = [];

    /**
     * a meta object containing non-standard meta-information about the error.
     * @var array
     */
    private $meta = [];

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ErrorObject
     */
    public function setId(string $id): ErrorObject
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param array $links
     * @return ErrorObject
     */
    public function setLinks(array $links): ErrorObject
    {
        $this->links = $links;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return ErrorObject
     */
    public function setStatus(string $status): ErrorObject
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return ErrorObject
     */
    public function setCode(string $code): ErrorObject
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ErrorObject
     */
    public function setTitle(string $title): ErrorObject
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * @param string $detail
     * @return ErrorObject
     */
    public function setDetail(string $detail): ErrorObject
    {
        $this->detail = $detail;
        return $this;
    }

    /**
     * @return array
     */
    public function getSource(): array
    {
        return $this->source;
    }

    /**
     * @param array $source
     * @return ErrorObject
     */
    public function setSource(array $source): ErrorObject
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     * @return ErrorObject
     */
    public function setMeta(array $meta): ErrorObject
    {
        $this->meta = $meta;
        return $this;
    }
}
