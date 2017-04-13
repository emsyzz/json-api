<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

/**
 * Single resource identifier relationship
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class SingleIdentifierRelationship extends AbstractRelationship
{
    /**
     * Resource identifier
     *
     * @var ResourceIdentifierObject
     */
    protected $identifier;

    /**
     * SingleIdentifierRelationship constructor.
     *
     * @param ResourceIdentifierObject $identifier
     * @param array                    $metadata
     */
    public function __construct(ResourceIdentifierObject $identifier, array $metadata = [])
    {
        $this->identifier = $identifier;
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
        return sprintf('Relationship contains [%s]', $this->identifier);
    }
}