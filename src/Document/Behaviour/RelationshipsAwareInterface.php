<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

use Mikemirten\Component\JsonApi\Document\AbstractRelationship;

/**
 * Interface of an object aware of relationships
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
interface RelationshipsAwareInterface
{
    /**
     * Set relationship
     *
     * @param string               $name
     * @param AbstractRelationship $relationship
     */
    public function setRelationship(string $name, AbstractRelationship $relationship);

    /**
     * Has relationship
     *
     * @param  string $name
     * @return bool
     */
    public function hasRelationship(string $name): bool;

    /**
     * Get relationship
     *
     * @param  string $name
     * @return AbstractRelationship
     */
    public function getRelationship(string $name): AbstractRelationship;

    /**
     * Get relationships
     *
     * @return AbstractRelationship[]
     */
    public function getRelationships(): array;
}