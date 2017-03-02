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
}