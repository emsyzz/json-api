<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Exception;

use Mikemirten\Component\JsonApi\Document\ResourceObject;

/**
 * AttributeNotFoundException
 *
 * @package Mikemirten\Component\JsonApi\Exception
 */
class AttributeNotFoundException extends JsonApiException
{
    /**
     * AttributeNotFoundException constructor.
     *
     * @param ResourceObject  $resource
     * @param string          $name
     * @param \Exception|null $previous
     */
    public function __construct(ResourceObject $resource, string $name, \Exception $previous = null)
    {
        $message = sprintf('Attribute "%s" not found inside of [%s].', $name, $resource);

        parent::__construct($message, 0, $previous);
    }
}