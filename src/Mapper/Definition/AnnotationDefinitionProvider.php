<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor\AnnotationProcessorInterface;

/**
 * Annotation Definition Provider based on the Doctrine-Annotation library
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class AnnotationDefinitionProvider implements DefinitionProviderInterface
{
    /**
     * Registered annotation processors
     *
     * @var AnnotationProcessorInterface[]
     */
    private $processors = [];

    /**
     * Cache of created definitions
     *
     * @var array
     */
    private $definitionCache = [];

    /**
     * Register annotation processor
     *
     * @param AnnotationProcessorInterface $processor
     */
    public function registerProcessor(AnnotationProcessorInterface $processor)
    {
        $this->processors[] = $processor;
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

        foreach ($this->processors as $processor)
        {
            $processor->process($reflection, $definition);
        }

        $parent = $reflection->getParentClass();

        if ($parent !== false) {
            $definition->merge($this->createDefinition($parent));
        }

        foreach ($reflection->getTraits() as $trait)
        {
            $definition->merge($this->createDefinition($trait));
        }

        return $definition;
    }
}