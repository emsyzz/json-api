<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

/**
 * Single resource document
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * @package Mikemirten\Component\JsonApi
 */
class SingleResourceDocument extends AbstractDocument
{
    /**
     * Resource
     *
     * @var ResourceObject
     */
    protected $resource;

    /**
     * SingleIdentifierDocument constructor.
     *
     * @param ResourceObject $resource
     * @param array          $metadata
     */
    public function __construct(ResourceObject $resource, array $metadata = [])
    {
        $this->resource = $resource;
        $this->metadata = $metadata;
    }

    /**
     * Get resource
     *
     * @return ResourceObject
     */
    public function getResource(): ResourceObject
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        $data['data'] = $this->getResource()->toArray();

        return $data;
    }

    /**
     * Cast to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('Document contains [%s]', $this->resource);
    }
}