<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\ResourceIdentifier;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Link;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;

/**
 * Processor of annotations of class
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor
 */
class ClassProcessor extends AbstractProcessor
{
    /**
     * Process annotations
     *
     * @param \ReflectionClass $reflection
     * @param Definition       $definition
     */
    public function process(\ReflectionClass $reflection, Definition $definition)
    {
        $annotations = $this->reader->getClassAnnotations($reflection);

        foreach ($annotations as $annotation)
        {
            if ($annotation instanceof Link) {
                $link = $this->createLink($annotation);

                $definition->addLink($link);
                continue;
            }

            if ($annotation instanceof ResourceIdentifier && $annotation->type !== null) {
                $definition->setType($annotation->type);
            }
        }
    }
}