<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\ResourceObject;

/**
 * AttributeNotFoundException
 *
 * @package Mikemirten\Component\JsonApi\Exception
 */
class AttributeNotFoundException extends DocumentException
{
    /**
     * Name of not found attribute
     *
     * @var string
     */
    protected $name;

    /**
     * Resource inside of which an attribute has not been found
     *
     * @var ResourceObject
     */
    protected $resource;

    /**
     * AttributeNotFoundException constructor.
     *
     * @param ResourceObject  $resource
     * @param string          $name
     * @param \Exception|null $previous
     */
    public function __construct(ResourceObject $resource, string $name, \Exception $previous = null)
    {
        $this->name     = $name;
        $this->resource = $resource;

        $message = sprintf('Attribute "%s" not found inside of [%s].', $name, $resource);

        parent::__construct($message, 0, $previous);
    }

    /**
     * Get name of not found attribute
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get resource inside of which an attribute has not been found
     *
     * @return ResourceObject
     */
    public function getResource(): ResourceObject
    {
        return $this->resource;
    }
}