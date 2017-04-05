<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Relationship as RelationshipAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Link as LinkAnnotation;

/**
 * Annotation Definition Provider based on the Doctrine-Annotation library
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class AnnotationDefinitionProvider implements DefinitionProviderInterface
{
    /**
     * Pattern of "resource" parameter
     */
    const RESOURCE_PATTERN = '~^(?<repository>[a-z_][a-z0-9_]*)\.(?<link>[a-z_][a-z0-9_]*)$~i';

    /**
     * Annotation classes ha been registered.
     *
     * @var bool
     */
    static private $annotationsRegistered = false;

    /**
     * Cache of created definitions
     *
     * @var array
     */
    private $definitionCache = [];

    /**
     * Register annotation classes.
     * Supports a medieval-aged way of "autoloading" for the Doctrine Annotation library.
     */
    static protected function registerAnnotations()
    {
        if (self::$annotationsRegistered === false) {
            AnnotationRegistry::registerFile(__DIR__ . '/Annotation/Relationship.php');
            AnnotationRegistry::registerFile(__DIR__ . '/Annotation/Link.php');

            self::$annotationsRegistered = true;
        }
    }

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
        self::registerAnnotations();

        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(string $class): Definition
    {
        if (! isset($this->definitionCache[$class])) {
            $this->definitionCache[$class] = $this->createDefinition($class);
        }

        return $this->definitionCache[$class];
    }

    /**
     * Create definition for given class
     *
     * @param  string $class
     * @return Definition
     */
    public function createDefinition(string $class): Definition
    {
        $definition = new Definition($class);
        $reflection = new \ReflectionClass($class);

        $this->processProperties($reflection, $definition);
        $this->processClassAnnotations($reflection, $definition);

        return $definition;
    }

    /**
     * Process properties of class
     *
     * @param \ReflectionClass $reflection
     * @param Definition       $definition
     */
    protected function processProperties(\ReflectionClass $reflection, Definition $definition)
    {
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
    }

    /**
     * Process annotations of class
     *
     * @param \ReflectionClass $reflection
     * @param Definition       $definition
     */
    protected function processClassAnnotations(\ReflectionClass $reflection, Definition $definition)
    {
        $annotations = $this->reader->getClassAnnotations($reflection);

        foreach ($annotations as $annotation)
        {
            if ($annotation instanceof LinkAnnotation) {
                $link = $this->createLink($annotation);

                $definition->addLink($link);
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

        $relationship = new Relationship($name, $type);
        $relationship->setPropertyName($property->getName());

        if ($annotation->resourceType !== null) {
            $relationship->setResourceType($annotation->resourceType);
        }

        $this->handleGetter($annotation, $relationship);
        $this->handleIdentifier($annotation, $relationship);
        $this->handleLinks($annotation, $relationship);

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
     * Create link by link's annotation
     *
     * @param  LinkAnnotation $annotation
     * @return Link
     */
    protected function createLink(LinkAnnotation $annotation): Link
    {
        if (! preg_match(self::RESOURCE_PATTERN, $annotation->resource, $matches)) {
            throw new \LogicException(sprintf('Invalid resource definition: "%s"', $annotation->resource));
        }

        $link = new Link(
            $annotation->name,
            $matches['repository'],
            $matches['link']
        );

        $link->setParameters($annotation->parameters);
        $link->setMetadata($annotation->metadata);

        return $link;
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