<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper;

use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Handler\IdentifierHandler\IdentifierHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\Handler\TypeHandler\TypeHandlerInterface;

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
     * Identifier handler
     *
     * @var IdentifierHandlerInterface
     */
    protected $identifierHandler;

    /**
     * Type handler
     *
     * @var TypeHandlerInterface
     */
    protected $typeHandler;

    /**
     * MappingContext constructor.
     *
     * @param ObjectMapper               $mapper
     * @param Definition                 $definition
     * @param IdentifierHandlerInterface $identifierHandler,
     * @param TypeHandlerInterface       $typeHandler
     */
    public function __construct(
        ObjectMapper               $mapper,
        Definition                 $definition,
        IdentifierHandlerInterface $identifierHandler,
        TypeHandlerInterface       $typeHandler
    ) {
        $this->mapper            = $mapper;
        $this->definition        = $definition;
        $this->identifierHandler = $identifierHandler;
        $this->typeHandler       = $typeHandler;
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

    /**
     * Get identifier handler
     *
     * @return IdentifierHandlerInterface
     */
    public function getIdentifierHandler(): IdentifierHandlerInterface
    {
        return $this->identifierHandler;
    }

    /**
     * Get type handler
     *
     * @return TypeHandlerInterface
     */
    public function getTypeHandler(): TypeHandlerInterface
    {
        return $this->typeHandler;
    }
}