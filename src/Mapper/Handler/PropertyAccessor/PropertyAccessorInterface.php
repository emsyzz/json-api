<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\PropertyAccessor;

/**
 * Interface of property accessor
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\PropertyAccessor
 */
interface PropertyAccessorInterface
{
    /**
     * Get value
     *
     * @param mixed  $resource
     * @param string $path
     */
    public function getValue($resource, string $path);
}