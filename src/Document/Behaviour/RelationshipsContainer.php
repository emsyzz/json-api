<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

use Mikemirten\Component\JsonApi\Document\AbstractRelationship;
use Mikemirten\Component\JsonApi\Exception\RelationshipNotFoundException;
use Mikemirten\Component\JsonApi\Exception\RelationshipOverrideException;

/**
 * Relationships-container behaviour
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
trait RelationshipsContainer
{
    /**
     * Relationships
     *
     * @var AbstractRelationship[]
     */
    protected $relationships = [];

    /**
     * Set relationship
     *
     * @param string               $name
     * @param AbstractRelationship $relationship
     */
    public function setRelationship(string $name, AbstractRelationship $relationship)
    {
        if (isset($this->relationships[$name])) {
            throw new RelationshipOverrideException($this, $name);
        }

        $this->relationships[$name] = $relationship;
    }

    /**
     * Has relationship
     *
     * @param  string $name
     * @return bool
     */
    public function hasRelationship(string $name): bool
    {
        return isset($this->relationships[$name]);
    }

    /**
     * Get relationship
     *
     * @param  string $name
     * @return AbstractRelationship
     */
    public function getRelationship(string $name): AbstractRelationship
    {
        if (isset($this->relationships[$name])) {
            return $this->relationships[$name];
        }

        throw new RelationshipNotFoundException($this, $name);
    }

    /**
     * Contains any relationships ?
     *
     * @return bool
     */
    public function hasRelationships(): bool
    {
        return count($this->relationships) > 0;
    }

    /**
     * Get relationships
     *
     * @return AbstractRelationship[]
     */
    public function getRelationships(): array
    {
        return $this->relationships;
    }

    /**
     * Remove relationship
     *
     * @param string $name
     */
    public function removeRelationship(string $name)
    {
        unset($this->relationships[$name]);
    }

    /**
     * Cast relationships to an array
     *
     * @return array
     */
    protected function relationshipsToArray(): array
    {
        $relationships = [];

        foreach ($this->relationships as $name => $relationship)
        {
            $relationships[$name] = $relationship->toArray();
        }

        return $relationships;
    }
}