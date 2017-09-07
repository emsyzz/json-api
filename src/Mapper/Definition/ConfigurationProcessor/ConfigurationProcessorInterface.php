<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;

interface ConfigurationProcessorInterface
{
    /**
     * Process configuration
     *
     * @param array      $configuration
     * @param Definition $definition
     */
    public function process(array $configuration, Definition $definition);
}