<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Link as LinkAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Link;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;

/**
 * Abstract processor
 * Contains shared methods and annotations registration
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor
 */
abstract class AbstractProcessor implements AnnotationProcessorInterface
{
    /**
     * Pattern of "resource" parameter of link annotation
     */
    const RESOURCE_PATTERN = '~^(?<repository>[a-z_][a-z0-9_]*)\.(?<link>[a-z_][a-z0-9_]*)$~i';

    /**
     * Annotation classes ha been registered.
     *
     * @var bool
     */
    static private $annotationsRegistered = false;

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
     * Register annotation classes.
     * Supports a medieval-aged way of "autoloading" for the Doctrine Annotation library.
     */
    static protected function registerAnnotations()
    {
        if (self::$annotationsRegistered === false) {
            AnnotationRegistry::registerFile(__DIR__ . '/../Annotation/ResourceIdentifier.php');
            AnnotationRegistry::registerFile(__DIR__ . '/../Annotation/Relationship.php');
            AnnotationRegistry::registerFile(__DIR__ . '/../Annotation/Attribute.php');
            AnnotationRegistry::registerFile(__DIR__ . '/../Annotation/Link.php');

            self::$annotationsRegistered = true;
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
     * Resolve getter of related object
     *
     * @param  \ReflectionProperty $property
     * @return string
     */
    protected function resolveGetter(\ReflectionProperty $property)
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
     * Resolve getter of related object
     *
     * @param  \ReflectionProperty $property
     * @return string | null
     */
    protected function resolveSetter(\ReflectionProperty $property)
    {
        $name  = $property->getName();
        $class = $property->getDeclaringClass();

        $setter = 'set' . ucfirst($name);

        if ($class->hasMethod($setter) && $class->getMethod($setter)->isPublic()) {
            return $setter;
        }
    }
}