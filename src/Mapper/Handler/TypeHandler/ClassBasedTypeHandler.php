<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\TypeHandler;

use Mikemirten\Component\JsonApi\Mapper\MappingContext;

/**
 * Type handler based on class of object
 *
 * @package Mikemirten\Component\JsonApi\ObjectTransformer\TypeHandler
 */
class ClassBasedTypeHandler implements TypeHandlerInterface
{
    /**
     * Full class name as a type
     *
     * @var bool
     */
    protected $fullName;

    /**
     * Type parts delimiter
     * Works only for "full-name" mode enabled
     *
     * @var string
     */
    protected $delimiter;

    /**
     * Cache of resolved types
     *
     * @var array
     */
    protected $resolvedCache = [];

    /**
     * ClassBasedTypeHandler constructor.
     *
     * @param bool   $fullName  Use full class name as a type
     * @param string $delimiter Type parts delimiter (only for enable "full-name" mode)
     */
    public function __construct(bool $fullName = true, string $delimiter = '.')
    {
        $this->fullName  = $fullName;
        $this->delimiter = $delimiter;
    }

    /**
     * {@inheritdoc}
     */
    public function getType($object, MappingContext $context): string
    {
        $class = get_class($object);

        if (! isset($this->resolvedCache[$class])) {
            $this->resolvedCache[$class] = $this->resolveType($class);
        }

        return $this->resolvedCache[$class];
    }

    /**
     * Resolve type by class
     *
     * @param  string $class
     * @return string
     */
    protected function resolveType(string $class): string
    {
        $name = str_replace(['\\', '_'], $this->delimiter, ucwords($class, '\\_'));

        if ($this->fullName) {
            return $name;
        }

        $pos = strrpos($name, $this->delimiter);

        if ($pos === false) {
            return $name;
        }

        return substr($name, $pos + 1);
    }
}