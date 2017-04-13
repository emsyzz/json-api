<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

/**
 * Resource Collection Document
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * @package Mikemirten\Component\JsonApi\Document
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
     * Get first resource from collection
     *
     * @return ResourceObject
     */
    public function getFirstResource(): ResourceObject
    {
        return reset($this->resources);
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
        return new \ArrayIterator($this->getResources());
    }

    /**
     * Cast to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $resources = [];

        foreach ($this->getResources() as $resource)
        {
            $resources[] = $resource->toArray();
        }

        $data = parent::toArray();

        $data['data'] = $resources;

        return $data;
    }

    /**
     * Cast to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'Document with collection of resources';
    }
}