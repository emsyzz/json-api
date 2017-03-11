<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\IdentifierCollectionAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\IdentifierCollectionContainer;

/**
 * Identifier Collection Relationship
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class IdentifierCollectionRelationship extends AbstractRelationship implements IdentifierCollectionAwareInterface
{
    use IdentifierCollectionContainer;

    /**
     * IdentifierCollectionRelationship constructor.
     *
     * @param array $metadata
     */
    public function __construct(array $metadata = [])
    {
        $this->metadata = $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        $data['data'] = $this->identifiersToArray();

        return $data;
    }
}