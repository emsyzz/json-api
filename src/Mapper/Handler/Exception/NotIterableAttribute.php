<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\Exception;

use Mikemirten\Component\JsonApi\Mapper\Definition\Attribute;

/**
 * Exception of non-iterable value of attribute declared as "many" (an iterable container).
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\Exception
 */
class NotIterableAttribute extends MappingHandlerException
{
    /**
     * @var Attribute
     */
    protected $definition;

    /**
     * NotIterableAttribute constructor.
     *
     * @param Attribute $attribute
     * @param mixed     $value
     */
    public function __construct(Attribute $attribute, $value)
    {
        $message = sprintf(
            'Attribute "%s" declared as many (an iterable container) contains %s which cannot be iterated.',
            $attribute->getName(),
            $this->resolveTypeDescription($value)
        );

        parent::__construct($message);
    }

    /**
     * Get definition of attribute caused the issue
     *
     * @return Attribute
     */
    public function getDefinition(): Attribute
    {
        return $this->definition;
    }

    /**
     * Resolve description of value's type
     *
     * @param  $value
     * @return string
     */
    protected function resolveTypeDescription($value): string
    {
        $type = gettype($value);

        if ($type === 'object') {
            return 'an instance of ' . get_class($value);
        }

        if ($type === 'integer') {
            return 'an integer';
        }

        return 'a ' . $type;
    }
}