<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper;

use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\Definition\DefinitionProviderInterface;
use Mikemirten\Component\JsonApi\Mapper\Handler\HandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\Handler\IdentifierHandler\IdentifierHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\Handler\TypeHandler\TypeHandlerInterface;

/**
 * Object mapper.
 *
 * Maps data between object and resource of document.
 * Can be helpful for serialization of objects to JSON API document.
 *
 * Each handler responsible for certain part of resource (links, metadata, relationships...).
 * Identifier and type handlers are specific and required to resolve id and type of resource.
 * Other handlers are optional.
 *
 * @package Mikemirten\Component\JsonApi\Mapper
 */
class ObjectMapper
{
    /**
     * Provider of mapping definition
     *
     * @var DefinitionProviderInterface
     */
    protected $definitionProvider;

    /**
     * Required identifier handler
     * Must always be present regardless of other handlers
     *
     * @var IdentifierHandlerInterface
     */
    protected $identifierHandler;

    /**
     * Required type handler
     * Must always be present regardless of other handlers
     *
     * @var TypeHandlerInterface
     */
    protected $typeHandler;

    /**
     * Mapping handlers
     *
     * @var HandlerInterface[]
     */
    protected $handlers = [];

    /**
     * ObjectMapper constructor.
     *
     * @param DefinitionProviderInterface $definitionProvider
     * @param IdentifierHandlerInterface  $identifierHandler
     * @param TypeHandlerInterface        $typeHandler
     */
    public function __construct(
        DefinitionProviderInterface $definitionProvider,
        IdentifierHandlerInterface  $identifierHandler,
        TypeHandlerInterface        $typeHandler
    ) {
        $this->definitionProvider = $definitionProvider;
        $this->identifierHandler  = $identifierHandler;
        $this->typeHandler        = $typeHandler;
    }

    /**
     * Add mapping handler
     *
     * @param HandlerInterface $handler
     */
    public function addHandler(HandlerInterface $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * Map object's data to resource
     *
     * @param  mixed $object
     * @return ResourceObject
     */
    public function toResource($object): ResourceObject
    {
        $context = $this->createContext(get_class($object));

        $id   = $this->identifierHandler->getIdentifier($object, $context);
        $type = $this->typeHandler->getType($object, $context);

        $resource = new ResourceObject($id, $type);

        foreach ($this->handlers as $handler)
        {
            $handler->toResource($object, $resource, $context);
        }

        return $resource;
    }

    /**
     * Map resource's data to provided object
     *
     * @param mixed $object
     * @param ResourceObject $resource
     */
    public function fromResource($object, ResourceObject $resource)
    {
        $context = $this->createContext(get_class($object));

        $this->identifierHandler->setIdentifier($object, $resource->getId(), $context);

        foreach ($this->handlers as $handler)
        {
            $handler->fromResource($object, $resource, $context);
        }
    }

    /**
     * Create mapping context for given class
     *
     * @param  string $class
     * @return MappingContext
     */
    protected function createContext(string $class): MappingContext
    {
        $definition = $this->definitionProvider->getDefinition($class);

        return new MappingContext($this, $definition, $this->identifierHandler, $this->typeHandler);
    }
}