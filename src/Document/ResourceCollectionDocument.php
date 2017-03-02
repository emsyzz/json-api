<?php
declare(strict_types = 1);

namespace Mikemirten\JsonApi\Component\Document;

/**
 * Resource Collection Document
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * @package Mikemirten\JsonApi\Component\Document
 */
class ResourceCollectionDocument extends AbstractDocument implements \IteratorAggregate
{
    /**
     * Resources
     *
     * @var ResourceObject[]
     */
    protected $resources = [];

    /**
     * ResourceCollectionDocument constructor.
     *
     * @param array $metadata
     */
    public function __construct(array $metadata = [])
    {
        $this->metadata = $metadata;
    }

    /**
     * Add resource
     *
     * @param ResourceObject $resource
     */
    public function addResource(ResourceObject $resource)
    {
        $this->resources[] = $resource;
    }

    /**
     * Get all resources
     *
     * @return ResourceObject[]
     */
    public function getResources(): array
    {
        return $this->resources;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->resources);
    }
}