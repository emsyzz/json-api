<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

/**
 * Identifier Collection Document
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class IdentifierCollectionDocument extends AbstractDocument implements \IteratorAggregate
{
    /**
     * Resource identifiers
     *
     * @var ResourceIdentifierObject[]
     */
    protected $identifiers;

    /**
     * Add resource identifier
     *
     * @param ResourceIdentifierObject $identifier
     */
    public function addIdentifier(ResourceIdentifierObject $identifier)
    {
        $this->identifiers[] = $identifier;
    }

    /**
     * Get resource identifiers
     *
     * @return ResourceIdentifierObject[]
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->identifiers);
    }
}