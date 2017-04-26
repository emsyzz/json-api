<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

/**
 * Definition of attribute
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class Attribute
{
    /**
     * Unique name of attribute inside of attribute-object
     *
     * @var string
     */
    protected $name;

    /**
     * Attribute constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Merge a attribute into this one
     * Named data of given attribute will override existing one in the case of names conflict
     *
     * @param self $attribute
     */
    public function merge(self $attribute)
    {

    }
}