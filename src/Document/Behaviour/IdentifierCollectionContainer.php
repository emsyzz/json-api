<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

use Mikemirten\Component\JsonApi\Document\ResourceIdentifierObject;

/**
 * Collection of resource identifiers container behaviour
 *
 * @see http://jsonapi.org/format/#document-links
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
trait IdentifierCollectionContainer
{
    /**
     * Resource identifiers
     *
     * @var ResourceIdentifierObject[]
     */
    protected $identifiers = [];

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
     * Get first identifier from collection
     *
     * @return ResourceIdentifierObject
     */
    public function getFirstIdentifier(): ResourceIdentifierObject
    {
        return reset($this->identifiers);
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