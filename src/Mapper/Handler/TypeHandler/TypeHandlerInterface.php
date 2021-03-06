<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\TypeHandler;

use Mikemirten\Component\JsonApi\Mapper\MappingContext;

/**
 * Interface of type handler
 * An implementation suppose to resolve type of passed object into a string
 *
 * @package Mikemirten\Component\JsonApi\ObjectTransformer\TypeHandler
 */
interface TypeHandlerInterface
{
    /**
     * Resolve type of object
     *
     * @param  mixed          $object
     * @param  MappingContext $context
     * @return string
     */
    public function getType($object, MappingContext $context): string;
}