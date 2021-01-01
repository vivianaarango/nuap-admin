<?php


namespace App\Libraries\Responders;

/**
 * Class JsonApiErrorsFormatter
 * @package App\Libraries\Responders
 */
class JsonApiErrorsFormatter
{
    /**
     * @var array
     */
    private $errors = [
        'errors' => [],
    ];

    /**
     * @param ErrorObject $errorObject
     * @return JsonApiErrorsFormatter
     */
    public function add(ErrorObject $errorObject): JsonApiErrorsFormatter
    {
        $error = [
            'status' => $errorObject->getStatus(),
            'title' => $errorObject->getTitle(),
            'detail' => $errorObject->getDetail(),
        ];

        if ($errorObject->getId() !== '') {
            $error['id'] = $errorObject->getId();
        }

        if (count($errorObject->getLinks()) > 0) {
            $error['links'] = $errorObject->getLinks();
        }

        if ($errorObject->getCode() !== '') {
            $error['code'] = $errorObject->getCode();
        }

        if (count($errorObject->getSource()) > 0) {
            $error['source'] = $errorObject->getSource();
        }

        if (count($errorObject->getMeta()) > 0) {
            $error['meta'] = $errorObject->getMeta();
        }

        $this->errors['errors'][] = $error;

        return $this;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->errors;
    }
}
