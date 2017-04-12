<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper;

use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;

/**
 * Context of mapping
 *
 * @package Mikemirten\Component\JsonApi\Mapper
 */
class MappingContext
{
    /**
     * Object mapper
     *
     * @var ObjectMapper
     */
    protected $mapper;

    /**
     * Mapping definition
     *
     * @var Definition
     */
    protected $definition;

    /**
     * MappingContext constructor.
     *
     * @param ObjectMapper $mapper
     * @param Definition   $definition
     */
    public function __construct(ObjectMapper $mapper, Definition $definition) {
        $this->mapper     = $mapper;
        $this->definition = $definition;
    }

    /**
     * Get object mapper
     *
     * @return ObjectMapper
     */
    public function getMapper(): ObjectMapper
    {
        return $this->mapper;
    }

    /**
     * Get mapping configuration
     *
     * @return Definition
     */
    public function getDefinition(): Definition
    {
        return $this->definition;
    }
}