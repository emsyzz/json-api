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
     * List of generic types
     *
     * @var array
     */
    protected static $genericTypes = ['integer', 'float', 'string', 'boolean'];

    /**
     * Data-type handlers
     *
     * @var DataTypeHandlerInterface[]
     */
    protected $registeredTypes = [];

    /**
     * Register data-type handler
     *
     * @param DataTypeHandlerInterface $handler
     */
    public function registerDataTypeHandler(DataTypeHandlerInterface $handler)
    {
        foreach ($handler->supports() as $name) {
            $this->registeredTypes[$name] = $handler;
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

        if (isset($this->registeredTypes[$type])) {
            return $this->processHandlerToResource($definition, $value);
        }

        if (in_array($type, self::$genericTypes)) {
            return $this->processGeneric($definition, $value);
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

        if (isset($this->registeredTypes[$type])) {
            return $this->processHandlerFromResource($definition, $value);
        }

        if (in_array($type, self::$genericTypes)) {
            return $this->processGeneric($definition, $value, false);
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
        $handler = $this->registeredTypes[$type];
        $parameters = $definition->getTypeParameters();

        if (! $definition->isMany()) {
            return $handler->toResource($value, $parameters);
        }

        if (! $value instanceof \Traversable && ! is_array($value)) {
            throw new NotIterableAttribute($definition, $value);
        }

        $collection = [];

        foreach ($value as $item) {
            $collection[] = $handler->toResource($item, $parameters);
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
        $handler = $this->registeredTypes[$type];
        $parameters = $definition->getTypeParameters();

        if (! $definition->isMany()) {
            return $handler->fromResource($value, $parameters);
        }

        if (! $value instanceof \Traversable && ! is_array($value)) {
            throw new NotIterableAttribute($definition, $value);
        }

        $collection = new \ArrayObject();

        foreach ($value as $item) {
            $collection[] = $handler->fromResource($item, $parameters);
        }

        return $collection;
    }

    /**
     * Process value by generic data-type
     * Both directions: from object to resource and from resource to object
     *
     * @param  Attribute $definition
     * @param  mixed     $value
     * @param  bool      $toResource
     * @return mixed
     */
    protected function processGeneric(Attribute $definition, $value, bool $toResource = true)
    {
        $type = $definition->getType();

        if (! $definition->isMany()) {
            return $this->processGenericType($type, $value);
        }

        if (! $value instanceof \Traversable && ! is_array($value)) {
            throw new NotIterableAttribute($definition, $value);
        }

        $collection = $toResource ? [] : new \ArrayObject();

        foreach ($value as $item) {
            $collection[] = $this->processGenericType($type, $item);
        }

        return $collection;
    }

    /**
     * Process generic data-types
     *
     * @param  string $type
     * @param  mixed  $value
     * @return bool|float|int|string
     */
    protected function processGenericType(string $type, $value)
    {
        if ($type === 'integer') {
            return (int) $value;
        }

        if ($type === 'float') {
            return (float) $value;
        }

        if ($type === 'boolean') {
            return (bool) $value;
        }

        return (string) $value;
    }
}