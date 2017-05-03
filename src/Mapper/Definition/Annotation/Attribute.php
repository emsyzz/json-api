<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\Annotation;

/**
 * Annotation defines a property as an attribute of JsonAPI-resource.
 *
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\Annotation
 */
class Attribute
{
    /**
     * Name of attribute
     *
     * @var string
     */
    public $name;

    /**
     * Data-type definition
     *
     * Format: "name-of-datatype[(parameter1, parameter2, parameterN)]"
     *
     * Correct definitions: "datetime", "datetime(Y-m-d)"
     *
     * @var string
     */
    public $type;

    /**
     * Getter-method to access attribute's value
     *
     * @var string
     */
    public $getter;
}