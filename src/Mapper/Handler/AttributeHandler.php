<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\Definition\Attribute;
use Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler\DataTypeHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;

/**
 * Attribute handler
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
class AttributeHandler implements HandlerInterface
{
    /**
     * List of generic types
     *
     * @var array
     */
    protected $genericTypes = ['integer', 'float', 'string', 'boolean'];

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
        foreach ($handler->supports() as $name)
        {
            $this->registeredTypes[$name] = $handler;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toResource($object, ResourceObject $resource, MappingContext $context)
    {
        $definitions = $context->getDefinition()->getAttributes();

        foreach ($definitions as $definition)
        {
            $this->processAttributeToResource($object, $resource, $definition);
        }
    }

    /**
     * Process attribute to resource mapping
     *
     * @param mixed          $object
     * @param ResourceObject $resource
     * @param Attribute      $definition
     */
    protected function processAttributeToResource($object, ResourceObject $resource, Attribute $definition)
    {
        $name   = $definition->getName();
        $getter = $definition->getGetter();

        $value = $object->$getter();

        if ($value === null && ! $definition->getProcessNull()) {
            return;
        }
        
        if ($definition->hasType()) {
            $value = $this->processTypeToResource($definition, $value);
        }

        $resource->setAttribute($name, $value);
    }

    /**
     * Process data-type
     *
     * @param  Attribute $definition
     * @param  mixed     $value
     * @return mixed
     */
    protected function processTypeToResource(Attribute $definition, $value)
    {
        $type = $definition->getType();

        if (isset($this->registeredTypes[$type])) {
            $parameters = $definition->getTypeParameters();

            return $this->registeredTypes[$type]->toResource($value, $parameters);
        }

        if (in_array($type, $this->genericTypes)) {
            return $this->processGenericType($type, $value);
        }

        throw new \LogicException(sprintf('Unable to handle unknown type "%s" of attribute "%s"', $type, $definition->getName()));
    }

    /**
     * Process data-type
     *
     * @param  Attribute $definition
     * @param  mixed     $value
     * @return mixed
     */
    protected function processResourceToType(Attribute $definition, $value)
    {
        $type = $definition->getType();

        if (isset($this->registeredTypes[$type])) {
            $parameters = $definition->getTypeParameters();

            return $this->registeredTypes[$type]->fromResource($value, $parameters);
        }

        if (in_array($type, $this->genericTypes)) {
            return $this->processGenericType($type, $value);
        }

        throw new \LogicException(sprintf('Unable to handle unknown type "%s" of attribute "%s"', $type, $definition->getName()));
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

        if ($type === 'bool') {
            return (bool) $value;
        }

        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function fromResource($object, ResourceObject $resource, MappingContext $context)
    {
        $definitions = $context->getDefinition()->getAttributes();

        foreach ($definitions as $definition)
        {
            if (! $definition->hasSetter()) {
                continue;
            }

            $this->processResourceToAttribute($object, $resource, $definition);
        }
    }

    /**
     * Process resource to attribute mapping
     *
     * @param mixed          $object
     * @param ResourceObject $resource
     * @param Attribute      $definition
     */
    protected function processResourceToAttribute($object, ResourceObject $resource, Attribute $definition)
    {
        $name = $definition->getName();

        if (! $resource->hasAttribute($name)) {
            return;
        }

        $value = $resource->getAttribute($name);

        if ($value === null && ! $definition->getProcessNull()) {
            return;
        }

        $setter = $definition->getSetter();

        if ($definition->hasType()) {
            $value = $this->processResourceToType($definition, $value);
        }

        $object->$setter($value);
    }
}