<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper;

use Mikemirten\Component\JsonApi\Mapper\Definition\DefinitionInterface;

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
     * @var DefinitionInterface
     */
    protected $definition;

    /**
     * MappingContext constructor.
     *
     * @param ObjectMapper        $mapper
     * @param DefinitionInterface $definition
     */
    public function __construct(ObjectMapper $mapper, DefinitionInterface $definition)
    {
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
     * @return DefinitionInterface
     */
    public function getDefinition(): DefinitionInterface
    {
        return $this->definition;
    }
}