<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Relationship as RelationshipAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\Relationship;

/**
 * Processor of relationships
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor
 */
class RelationshipProcessor extends AbstractProcessor
{
    /**
     * Process properties of class
     *
     * @param \ReflectionClass $reflection
     * @param Definition       $definition
     */
    public function process(\ReflectionClass $reflection, Definition $definition): void
    {
        foreach ($reflection->getProperties() as $property)
        {
            $this->processProperty($property, $definition);
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
            if ($annotation instanceof RelationshipAnnotation) {
                $relationship = $this->createRelationship($annotation, $property);

                $definition->addRelationship($relationship);
            }
        }
    }

    /**
     * Process relationship
     *
     * @param  RelationshipAnnotation $annotation
     * @param  \ReflectionProperty    $property
     * @return Relationship
     */
    protected function createRelationship(RelationshipAnnotation $annotation, \ReflectionProperty $property): Relationship
    {
        $name = ($annotation->name === null)
            ? $property->getName()
            : $annotation->name;

        $type = $this->resolveType($annotation);

        $getter = ($annotation->getter === null)
            ? $this->resolveGetter($property)
            : $annotation->getter;

        $relationship = new Relationship($name, $type, $getter);
        $relationship->setPropertyName($property->getName());

        $this->handleLinks($annotation, $relationship);
        $this->handleDataControl($annotation, $relationship);

        return $relationship;
    }

    /**
     * Handle links
     *
     * @param RelationshipAnnotation $annotation
     * @param Relationship           $relationship
     */
    protected function handleLinks(RelationshipAnnotation $annotation, Relationship $relationship)
    {
        foreach ($annotation->links as $linkAnnotation)
        {
            $link = $this->createLink($linkAnnotation);

            $relationship->addLink($link);
        }
    }

    /**
     * Handle control of data-section
     *
     * @param RelationshipAnnotation $annotation
     * @param Relationship           $relationship
     */
    protected function handleDataControl(RelationshipAnnotation $annotation, Relationship $relationship)
    {
        $relationship->setIncludeData($annotation->dataAllowed);
        $relationship->setDataLimit($annotation->dataLimit);
    }

    /**
     * Resolve type of relationship
     *
     * @param  RelationshipAnnotation $annotation
     * @return int
     */
    protected function resolveType(RelationshipAnnotation $annotation): int
    {
        if ($annotation->type === RelationshipAnnotation::TYPE_ONE) {
            return Relationship::TYPE_X_TO_ONE;
        }

        if ($annotation->type === RelationshipAnnotation::TYPE_MANY) {
            return Relationship::TYPE_X_TO_MANY;
        }

        throw new \LogicException(sprintf('Invalid type of relation "%s" defined.', $annotation->type));
    }
}