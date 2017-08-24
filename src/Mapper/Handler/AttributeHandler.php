<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\Definition\Attribute;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;

/**
 * Attribute handler
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
class AttributeHandler implements HandlerInterface
{
    /**
     * @var DataTypeManager
     */
    protected $typeManager;

    /**
     * AttributeHandler constructor.
     *
     * @param DataTypeManager $typeManager
     */
    public function __construct(DataTypeManager $typeManager)
    {
        $this->typeManager = $typeManager;
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

        $value = $this->typeManager->toResource($definition, $value);

        $resource->setAttribute($name, $value);
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

        $value  = $this->typeManager->fromResource($definition, $value);
        $setter = $definition->getSetter();

        $object->$setter($value);
    }
}