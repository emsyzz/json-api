<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\Exception\DefinitionNotFoundException;

/**
 * A definition provider aggregates a number of definition providers
 * Merges definitions from registered providers
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class MergedProvider implements DefinitionProviderInterface
{
    /**
     * @var DefinitionProviderInterface[]
     */
    protected $providers = [];

    /**
     * @var Definition[]
     */
    protected $definitions = [];

    /**
     * Add definition provider
     *
     * @param DefinitionProviderInterface $provider
     */
    public function addProvider(DefinitionProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(string $class): Definition
    {
        if (! isset($this->definitions[$class])) {
            $this->definitions[$class] = $this->createDefinition($class);
        }

        return $this->definitions[$class];
    }

    /**
     * Create definition
     *
     * @param  string $class
     * @return Definition
     */
    public function createDefinition(string $class): Definition
    {
        $definition = new Definition($class);

        foreach ($this->providers as $provider)
        {
            try {
                $definitionPartial = $provider->getDefinition($class);
            } catch (DefinitionNotFoundException $exception) {
                continue;
            }

            $definition->merge($definitionPartial);
        }

        return $definition;
    }
}