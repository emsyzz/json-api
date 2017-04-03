<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

/**
 * Interface of definition provider
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
interface DefinitionProviderInterface
{
    /**
     * Get mapping definition for given class
     *
     * @param  string $class
     * @return Definition
     */
    public function getDefinition(string $class): Definition;
}