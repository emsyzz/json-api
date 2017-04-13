<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

/**
 * Single resource identifier document
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class SingleIdentifierDocument extends AbstractDocument
{
    /**
     * Resource identifier
     *
     * @var ResourceIdentifierObject
     */
    protected $identifier;

    /**
     * SingleResourceDocument constructor.
     *
     * @param ResourceIdentifierObject $resource
     * @param array                    $metadata
     */
    public function __construct(ResourceIdentifierObject $resource, array $metadata = [])
    {
        $this->identifier = $resource;
        $this->metadata   = $metadata;
    }

    /**
     * Get resource identifier
     *
     * @return ResourceIdentifierObject
     */
    public function getIdentifier(): ResourceIdentifierObject
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        $data['data'] = $this->getIdentifier()->toArray();

        return $data;
    }

    /**
     * Cast to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('Document contains [%s]', $this->identifier);
    }
}