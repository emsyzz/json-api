<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\Exception;

use Mikemirten\Component\JsonApi\Mapper\Definition\Attribute;

/**
 * Exception of unknown data-type
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\Exception
 */
class UnknownDataTypeException extends MappingHandlerException
{
    /**
     * @var Attribute
     */
    protected $definition;

    /**
     * UnknownTypeException constructor.
     *
     * @param Attribute $definition
     */
    public function __construct(Attribute $definition)
    {
        $this->definition = $definition;

        $message = sprintf(
            'Unable to process unknown data-type "%s" of attribute "%s"',
            $definition->getType(),
            $definition->getName()
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
}