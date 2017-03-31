<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

/**
 * Interface of mapping definition.
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
interface DefinitionInterface
{
    /**
     * Get attributes
     *
     * @return Attribute[]
     */
    public function getAttributes(): array;

    /**
     * Get relationships
     *
     * @return Relationship[]
     */
    public function getRelationships(): array;
}