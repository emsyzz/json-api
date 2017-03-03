<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

use Mikemirten\Component\JsonApi\Document\ResourceIdentifierObject;

/**
 * Interface of an object aware of collection of resource identifiers
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
interface IdentifierCollectionAwareInterface extends \IteratorAggregate
{
    /**
     * Add resource identifier
     *
     * @param ResourceIdentifierObject $identifier
     */
    public function addIdentifier(ResourceIdentifierObject $identifier);

    /**
     * Get first identifier from collection
     *
     * @return ResourceIdentifierObject
     */
    public function getFirstIdentifier(): ResourceIdentifierObject;

    /**
     * Get resource identifiers
     *
     * @return ResourceIdentifierObject[]
     */
    public function getIdentifiers(): array;
}