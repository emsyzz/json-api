<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;

/**
 * Processor of configuration of class
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor
 */
class ClassProcessor extends AbstractProcessor
{
    /**
     * {@inheritdoc}
     */
    public function process(array $config, Definition $definition)
    {
        if (isset($config['type'])) {
            $definition->setType($config['type']);
        }

        if (! isset($config['links'])) {
            return;
        }

        foreach ($config['links'] as $linkName => $linkData)
        {
            $link = $this->createLink($linkName, $linkData);

            $definition->addLink($link);
        }
    }
}