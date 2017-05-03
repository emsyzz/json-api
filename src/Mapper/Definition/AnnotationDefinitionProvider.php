<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\ResourceIdentifier as ResourceIdentifierAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Relationship as RelationshipAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Attribute as AttributeAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Link as LinkAnnotation;

/**
 * Annotation Definition Provider based on the Doctrine-Annotation library
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class AnnotationDefinitionProvider implements DefinitionProviderInterface
{
    /**
     * Pattern of "resource" parameter of link annotation
     */
    const RESOURCE_PATTERN = '~^(?<repository>[a-z_][a-z0-9_]*)\.(?<link>[a-z_][a-z0-9_]*)$~i';

    /**
     * Pattern of "type" parameter of attribute annotation
     */
    const DATATYPE_PATTERN = '~^(?<type>[a-z_][a-z0-9_]*)\s*(?:\((?<params>[^\)]*)\))?$~i';

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
            AnnotationRegistry::registerFile(__DIR__ . '/Annotation/Attribute.php');
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
            $reflection = new \ReflectionClass($class);

            $this->definitionCache[$class] = $this->createDefinition($reflection);
        }

        return $this->definitionCache[$class];
    }

    /**
     * Create definition for given class
     *
     * @param  \ReflectionClass $reflection
     * @return Definition
     */
    protected function createDefinition(\ReflectionClass $reflection): Definition
    {
        $definition = new Definition($reflection->getName());

        $this->processProperties($reflection, $definition);
        $this->processClassAnnotations($reflection, $definition);

        $parent = $reflection->getParentClass();

        if ($parent !== false) {
            $definition->merge($this->createDefinition($parent));
        }

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
            $annotations = $this->reader->getPropertyAnnotations($property);

            foreach ($annotations as $annotation)
            {
                if ($annotation instanceof AttributeAnnotation) {
                    $attribute = $this->createAttribute($annotation, $property);

                    $definition->addAttribute($attribute);
                    continue;
                }

                if ($annotation instanceof RelationshipAnnotation) {
                    $relationship = $this->createRelationship($annotation, $property);

                    $definition->addRelationship($relationship);
                }
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
                continue;
            }

            if ($annotation instanceof ResourceIdentifierAnnotation) {
                $this->handlerResourceIdentifier($annotation, $definition);
            }
        }
    }

    /**
     * Handler resource identifier
     *
     * @param ResourceIdentifierAnnotation $annotation
     * @param Definition                   $definition
     */
    protected function handlerResourceIdentifier(ResourceIdentifierAnnotation $annotation, Definition $definition)
    {
        if ($annotation->type !== null) {
            $definition->setType($annotation->type);
        }
    }

    /**
     * Create attribute
     *
     * @param  AttributeAnnotation $annotation
     * @param  \ReflectionProperty $property
     * @return Attribute
     */
    protected function createAttribute(AttributeAnnotation $annotation, \ReflectionProperty $property)
    {
        $name = ($annotation->name === null)
            ? $property->getName()
            : $annotation->name;

        $getter = ($annotation->getter === null)
            ? $this->resolveGetter($property)
            : $annotation->getter;

        $attribute = new Attribute($name, $getter);
        $attribute->setPropertyName($property->getName());

        if ($annotation->type !== null) {
            $this->processDataType($annotation->type, $attribute);
        }

        return $attribute;
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
     * Resolve getter of related object
     *
     * @param  \ReflectionProperty $property
     * @return string
     */
    protected function resolveGetter(\ReflectionProperty $property): string
    {
        $name  = $property->getName();
        $class = $property->getDeclaringClass();

        foreach (['get', 'is'] as $prefix)
        {
            $getter = $prefix . ucfirst($name);

            if ($class->hasMethod($getter) && $class->getMethod($getter)->isPublic()) {
                return $getter;
            }
        }

        throw new \LogicException(sprintf(
            'Getter-method for the property "%s" cannot be resolved automatically. ' .
            'Probably there is no get%2$s() or is%2$s() method or it is not public.',
            $name, ucfirst($name)
        ));
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