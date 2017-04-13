<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\ResourceObject;

/**
 * AttributeOverrideException
 *
 * @package Mikemirten\Component\JsonApi\Exception
 */
class AttributeOverrideException extends DocumentException
{
    /**
     * AttributeOverrideException constructor.
     *
     * @param ResourceObject  $resource
     * @param string          $name
     * @param \Exception|null $previous
     */
    public function __construct(ResourceObject $resource, string $name, \Exception $previous = null)
    {
        $message = sprintf('Attribute "%s" already exists inside of [%s]. To set new one, the old one must be removed.', $name, $resource);

        parent::__construct($message, 0, $previous);
    }
}