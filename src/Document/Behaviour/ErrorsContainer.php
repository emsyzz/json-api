<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

use Mikemirten\Component\JsonApi\Document\ErrorObject;

/**
 * Errors-container behaviour
 *
 * @see http://jsonapi.org/format/#errors
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
trait ErrorsContainer
{
    /**
     * @var ErrorObject[];
     */
    protected $errors = [];

    /**
     * Add error
     *
     * @param ErrorObject $error
     */
    public function addError(ErrorObject $error)
    {
        $this->errors[] = $error;
    }

    /**
     * Contains any errors ?
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    /**
     * Get all errors
     *
     * @return ErrorObject[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Cast errors to an array
     *
     * @return array
     */
    protected function errorsToArray(): array
    {
        $errors = [];

        foreach ($this->errors as $error)
        {
            $errors[] = $error->toArray();
        }

        return $errors;
    }
}