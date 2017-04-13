<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

/**
 * No data relationship
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class NoDataRelationship extends AbstractRelationship
{
    /**
     * NoDataRelationship constructor.
     *
     * @param array $metadata
     */
    public function __construct(array $metadata = [])
    {
        $this->metadata = $metadata;
    }

    /**
     * Cast to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'Relationship with no data';
    }
}