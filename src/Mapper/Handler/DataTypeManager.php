<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler\DataTypeHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\Handler\Exception\NotIterableAttribute;
use Mikemirten\Component\JsonApi\Mapper\Handler\Exception\UnknownDataTypeException;
use Mikemirten\Component\JsonApi\Mapper\Definition\Attribute;

/**
 * Data-type manager
 * Serves for data processing of types
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
class DataTypeManager
{
    /**
     * Data-type handlers
     *
     * @var DataTypeHandlerInterface[]
     */
    protected $handlers = [];

    /**
     * Register data-type handler
     *
     * @param DataTypeHandlerInterface $handler
     */
    public function registerDataTypeHandler(DataTypeHandlerInterface $handler)
    {
        foreach ($handler->supports() as $name) {
            $this->handlers[$name] = $handler;
        }
    }

    /**
     * Process data-type
     *
     * @param  Attribute $definition
     * @param  mixed $value
     * @return mixed
     */
    public function toResource(Attribute $definition, $value)
    {
        if (! $definition->hasType()) {
            return $this->processNotTypedToResource($definition, $value);
        }

        $type = $definition->getType();

        if (isset($this->handlers[$type])) {
            return $this->processHandlerToResource($definition, $value);
        }

        throw new UnknownDataTypeException($definition);
    }

    /**
     * Process data-type
     *
     * @param  Attribute $definition
     * @param  mixed $value
     * @return mixed
     */
    public function fromResource(Attribute $definition, $value)
    {
        if (! $definition->hasType()) {
            return $this->processNotTypedFromResource($definition, $value);
        }

        $type = $definition->getType();

        if (isset($this->handlers[$type])) {
            return $this->processHandlerFromResource($definition, $value);
        }

        throw new UnknownDataTypeException($definition);
    }

    /**
     * Process not-typed value
     * Both directions: from object to resource and from resource to object
     *
     * @param  Attribute $definition
     * @param  mixed $value
     * @return mixed
     * @throws NotIterableAttribute
     */
    protected function processNotTypedToResource(Attribute $definition, $value)
    {
        if (! $definition->isMany()) {
            return $value;
        }

        if ($value instanceof \Traversable) {
            return iterator_to_array($value, false);
        }

        if (is_array($value)) {
            return $value;
        }

        throw new NotIterableAttribute($definition, $value);
    }

    /**
     * Process not-typed value
     * Both directions: from object to resource and from resource to object
     *
     * @param  Attribute $definition
     * @param  mixed $value
     * @return mixed
     * @throws NotIterableAttribute
     */
    protected function processNotTypedFromResource(Attribute $definition, $value)
    {
        if (! $definition->isMany()) {
            return $value;
        }

        if ($value instanceof \Traversable) {
            return $value;
        }

        if (is_array($value)) {
            return new \ArrayIterator($value);
        }

        throw new NotIterableAttribute($definition, $value);
    }

    /**
     * Process value by registered data-type handler.
     * From object to resource.
     *
     * @param  Attribute $definition
     * @param  mixed $value
     * @return mixed
     * @throws NotIterableAttribute
     */
    protected function processHandlerToResource(Attribute $definition, $value)
    {
        $type = $definition->getType();
        $handler = $this->handlers[$type];
        $parameters = $definition->getTypeParameters();

        if (! $definition->isMany()) {
            return $handler->toResource($value, $type, $parameters);
        }

        if (! $value instanceof \Traversable && ! is_array($value)) {
            throw new NotIterableAttribute($definition, $value);
        }

        $collection = [];

        foreach ($value as $item) {
            $collection[] = $handler->toResource($item, $type, $parameters);
        }

        return $collection;
    }

    /**
     * Process value by registered data-type handler.
     * From resource to object.
     *
     * @param  Attribute $definition
     * @param  mixed $value
     * @return mixed
     * @throws NotIterableAttribute
     */
    protected function processHandlerFromResource(Attribute $definition, $value)
    {
        $type = $definition->getType();
        $handler = $this->handlers[$type];
        $parameters = $definition->getTypeParameters();

        if (! $definition->isMany()) {
            return $handler->fromResource($value, $type, $parameters);
        }

        if (! $value instanceof \Traversable && ! is_array($value)) {
            throw new NotIterableAttribute($definition, $value);
        }

        $collection = new \ArrayObject();

        foreach ($value as $item) {
            $collection[] = $handler->fromResource($item, $type, $parameters);
        }

        return $collection;
    }
}