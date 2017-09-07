<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configuration (schema) of mapping definition
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class DefinitionConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder    = new TreeBuilder();
        $definition = $builder->root('definition')->children();

        $definition->scalarNode('type')
            ->cannotBeEmpty();

        $this->defineLinks($definition);
        $this->defineAttributes($definition);
        $this->defineRelationships($definition);

        return $builder;
    }

    /**
     * Define "attributes" section of configuration
     *
     * @param NodeBuilder $builder
     */
    protected function defineAttributes(NodeBuilder $builder)
    {
        $builder->arrayNode('attributes')
            ->useAttributeAsKey('')
            ->prototype('array')
            ->children()

            ->scalarNode('type')
                ->cannotBeEmpty()
            ->end()

            ->scalarNode('getter')
                ->cannotBeEmpty()
            ->end()

            ->scalarNode('setter')
                ->cannotBeEmpty()
            ->end()

            ->booleanNode('processNull')
                ->defaultFalse()
            ->end();
    }

    /**
     * Define "relationships" section of configuration
     *
     * @param NodeBuilder $builder
     */
    protected function defineRelationships(NodeBuilder $builder)
    {
        $relationships = $builder->arrayNode('relationships')
            ->useAttributeAsKey('')
            ->prototype('array')
            ->children()

            ->enumNode('type')
                ->values(['one', 'many'])
                ->cannotBeEmpty()
                ->defaultValue('one')
            ->end()

            ->scalarNode('getter')
                ->cannotBeEmpty()
            ->end()

            ->booleanNode('dataAllowed')
                ->defaultFalse()
            ->end()

            ->integerNode('dataLimit')
                ->defaultValue(0)
            ->end();

        $this->defineLinks($relationships);
    }

    /**
     * Define "links" section of configuration
     *
     * @param NodeBuilder $builder
     */
    protected function defineLinks(NodeBuilder $builder)
    {
        $builder->arrayNode('links')
            ->useAttributeAsKey('')
            ->prototype('array')
            ->children()

            ->scalarNode('resource')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()

            ->arrayNode('parameters')
                ->prototype('scalar')->end()
            ->end()

            ->arrayNode('metadata')
                ->prototype('scalar')->end()
            ->end();
    }
}