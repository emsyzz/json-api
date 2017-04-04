<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Doctrine\Common\Annotations\Reader;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Relationship as RelationshipAnnotation;

/**
 * Annotation Definition Provider based on the Doctrine-Annotation library
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class AnnotationDefinitionProvider implements DefinitionProviderInterface
{
    /**
     * Doctrine annotation reader
     *
     * @var Reader
     */
    protected $reader;

    /**
     * AnnotationDefinitionProvider constructor.
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(string $class): Definition
    {
        $definition = new Definition();
        $reflection = new \ReflectionClass($class);

        foreach ($reflection->getProperties() as $property)
        {
            $relationshipAnnotation = $this->reader->getPropertyAnnotation(
                $property,
                RelationshipAnnotation::class
            );

            if ($relationshipAnnotation !== null) {
                $relationship = $this->createRelationship($relationshipAnnotation, $property);

                $definition->addRelationship($relationship);
            }
        }

        return $definition;
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

        $type = $this->resoleType($annotation);

        $relationship = new Relationship($name, $type);
        $relationship->setPropertyName($property->getName());

        if ($annotation->resourceType !== null) {
            $relationship->setResourceType($annotation->resourceType);
        }

        $this->handleGetter($annotation, $relationship);
        $this->handleIdentifier($annotation, $relationship);

        return $relationship;
    }

    /**
     * Handler getter of related object
     *
     * @param RelationshipAnnotation $annotation
     * @param Relationship           $relationship
     */
    protected function handleGetter(RelationshipAnnotation $annotation, Relationship $relationship)
    {
        if ($annotation->getter === null) {
            $name   = $relationship->getPropertyName();
            $getter = 'get' . ucfirst($name);

            $relationship->setGetter($getter);
            return;
        }

        $relationship->setGetter($annotation->getter);
    }

    /**
     * Handle identifier
     *
     * @param RelationshipAnnotation $annotation
     * @param Relationship           $relationship
     */
    protected function handleIdentifier(RelationshipAnnotation $annotation, Relationship $relationship)
    {
        if ($annotation->idGetter !== null) {
            $relationship->setIdentifierGetter($annotation->idGetter);
            return;
        }

        if ($annotation->idProperty !== null) {
            $getter = 'get' . ucfirst($annotation->idProperty);

            $relationship->setIdentifierGetter($getter);
            return;
        }
    }

    /**
     * Resolve type of relationship
     *
     * @param  RelationshipAnnotation $annotation
     * @return int
     */
    protected function resoleType(RelationshipAnnotation $annotation): int
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