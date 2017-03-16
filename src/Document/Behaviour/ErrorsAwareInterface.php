<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

use Mikemirten\Component\JsonApi\Document\ErrorObject;

/**
 * Interface of an object aware of errors
 *
 * @see http://jsonapi.org/format/#errors
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
interface ErrorsAwareInterface
{
    /**
     * Add error
     *
     * @param ErrorObject $error
     */
    public function addError(ErrorObject $error);

    /**
     * Contains any errors ?
     *
     * @return bool
     */
    public function hasErrors(): bool;

    /**
     * Get all errors
     *
     * @return ErrorObject[]
     */
    public function getErrors(): array;
}