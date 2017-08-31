<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Attribute as AttributeAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\Attribute;

/**
 * Processor of attributes
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor
 */
class AttributeProcessor extends AbstractProcessor
{
    /**
     * Pattern of "type" parameter of attribute annotation
     */
    const DATATYPE_PATTERN = '~^(?<type>[a-z_][a-z0-9_]*)\s*(?:\((?<params>[^\)]*)\))?$~i';

    /**
     * Process annotations
     *
     * @param \ReflectionClass $reflection
     * @param Definition       $definition
     */
    public function process(\ReflectionClass $reflection, Definition $definition)
    {
        foreach ($reflection->getProperties() as $property)
        {
            $this->processProperty($property, $definition);
        }

        foreach ($reflection->getMethods() as $method)
        {
            $this->processMethod($method, $definition);
        }
    }

    /**
     * Process property of class
     *
     * @param \ReflectionProperty $property
     * @param Definition          $definition
     */
    protected function processProperty(\ReflectionProperty $property, Definition $definition)
    {
        $annotations = $this->reader->getPropertyAnnotations($property);

        foreach ($annotations as $annotation)
        {
            if ($annotation instanceof AttributeAnnotation) {
                $attribute = $this->createAttributeByProperty($annotation, $property);

                $definition->addAttribute($attribute);
                continue;
            }
        }
    }

    /**
     * Process method of class
     *
     * @param \ReflectionMethod $method
     * @param Definition        $definition
     */
    protected function processMethod(\ReflectionMethod $method, Definition $definition)
    {
        $annotations = $this->reader->getMethodAnnotations($method);

        foreach ($annotations as $annotation)
        {
            if ($annotation instanceof AttributeAnnotation) {
                $this->validateMethodAttribute($annotation, $method);

                $attribute = $this->createAttributeByMethod($annotation, $method);
                $definition->addAttribute($attribute);
            }
        }
    }

    /**
     * Validate method with attribute definition
     *
     * @param  AttributeAnnotation $annotation
     * @param  \ReflectionMethod   $method
     * @throws \LogicException
     */
    protected function validateMethodAttribute(AttributeAnnotation $annotation, \ReflectionMethod $method)
    {
        if (! $method->isPublic()) {
            throw new \LogicException(sprintf(
                'Attribute annotation can be applied only to non public method "%s".',
                $method->getName()
            ));
        }

        if ($annotation->getter !== null) {
            throw new \LogicException(sprintf(
                'The "getter" property of Attribute annotation applied to method "%s" is useless.',
                $method->getName()
            ));
        }
    }

    /**
     * Create attribute by annotation of property
     *
     * @param  AttributeAnnotation $annotation
     * @param  \ReflectionProperty $property
     * @return Attribute
     */
    protected function createAttributeByProperty(AttributeAnnotation $annotation, \ReflectionProperty $property): Attribute
    {
        $name = ($annotation->name === null)
            ? $property->getName()
            : $annotation->name;

        $getter = ($annotation->getter === null)
            ? $this->resolveGetter($property)
            : $annotation->getter;

        $setter = ($annotation->setter === null)
            ? $this->resolveSetter($property)
            : $annotation->setter;

        $attribute = new Attribute($name, $getter);
        $attribute->setPropertyName($property->getName());

        if ($setter !== null) {
            $attribute->setSetter($setter);
        }

        $this->processAttributeOptions($annotation, $attribute);

        return $attribute;
    }

    /**
     * Process optional properties of attribute
     *
     * @param AttributeAnnotation $annotation
     * @param Attribute           $attribute
     */
    protected function processAttributeOptions(AttributeAnnotation $annotation, Attribute $attribute)
    {
        if ($annotation->type !== null) {
            $this->processDataType($annotation->type, $attribute);
        }

        if ($annotation->many !== null) {
            $attribute->setMany($annotation->many);
        }

        if ($annotation->processNull !== null) {
            $attribute->setProcessNull($annotation->processNull);
        }
    }

    /**
     * Create attribute by annotation of method
     *
     * @param  AttributeAnnotation $annotation
     * @param  \ReflectionMethod   $method
     * @return Attribute
     */
    protected function createAttributeByMethod(AttributeAnnotation $annotation, \ReflectionMethod $method): Attribute
    {
        $name = ($annotation->name === null)
            ? $this->resolveNameByMethod($method)
            : $annotation->name;

        $attribute = new Attribute($name, $method->getName());

        if ($annotation->type !== null) {
            $this->processDataType($annotation->type, $attribute);
        }

        return $attribute;
    }

    /**
     * Resolve name of attribute by method
     *
     * @param  \ReflectionMethod $method
     * @return string
     */
    protected function resolveNameByMethod(\ReflectionMethod $method): string
    {
        $name = $method->getName();

        if (preg_match('~^(?:get|is)(?<name>[a-z0-9_]+)~i', $name, $matches)) {
            return lcfirst($matches['name']);
        }

        return $name;
    }

    /**
     * Process data-type
     *
     * @param string    $definition
     * @param Attribute $attribute
     */
    protected function processDataType(string $definition, Attribute $attribute)
    {
        if (! preg_match(self::DATATYPE_PATTERN, $definition, $matches)) {
            throw new \LogicException(sprintf('Data-type definition "%s" is invalid.', $definition));
        }

        $attribute->setType($matches['type']);

        if (empty($matches['params'])) {
            return;
        }

        $parameters = explode(',', $matches['params']);
        $parameters = array_map('trim', $parameters);

        $attribute->setTypeParameters($parameters);
    }
}