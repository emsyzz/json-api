<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;

/**
 * Interface of annotation's processor
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor
 */
interface AnnotationProcessorInterface
{
    /**
     * Process annotations
     *
     * @param \ReflectionClass $reflection
     * @param Definition       $definition
     */
    public function process(\ReflectionClass $reflection, Definition $definition);
}