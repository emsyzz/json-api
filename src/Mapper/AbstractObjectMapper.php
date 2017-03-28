<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper;

use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\IdentifierHandler\IdentifierHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\TypeHandler\TypeHandlerInterface;

/**
 * Abstract mapper
 * Contains implementation of ID and type handling, expects data mapping from extension
 *
 * @package Mikemirten\Component\JsonApi\ObjectTransformer
 */
abstract class AbstractObjectMapper implements ObjectMapperInterface
{
    /**
     * Identifier of resource handler
     *
     * @var IdentifierHandlerInterface
     */
    protected $identifierHandler;

    /**
     * Type of resource handler
     *
     * @var TypeHandlerInterface
     */
    protected $typeHandler;

    /**
     * AbstractObjectMapper constructor.
     *
     * @param IdentifierHandlerInterface $identifierHandler
     * @param TypeHandlerInterface       $typeHandler
     */
    public function __construct(IdentifierHandlerInterface $identifierHandler, TypeHandlerInterface $typeHandler)
    {
        $this->identifierHandler = $identifierHandler;
        $this->typeHandler       = $typeHandler;
    }

    /**
     * {@inheritdoc}
     */
    final public function toResource($object): ResourceObject
    {
        $id   = $this->identifierHandler->getIdentifier($object);
        $type = $this->typeHandler->getType($object);

        $attributes = $this->toArray($object);

        return new ResourceObject($id, $type, $attributes);
    }

    /**
     * {@inheritdoc}
     */
    final public function fromResource($object, ResourceObject $resource)
    {
        $this->identifierHandler->setIdentifier($object, $resource->getId());

        $this->fromArray($object, $resource->getAttributes());
    }

    /**
     * Map properties of object to an array
     * [name => value]
     *
     * @param  mixed $object
     * @return array
     */
    abstract protected function toArray($object): array;

    /**
     * Map an array of data to object
     * [name => value]
     *
     * @param  mixed $object
     * @param  array $data
     */
    abstract protected function fromArray($object, array $data);
}