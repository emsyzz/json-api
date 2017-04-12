<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\Annotation;

/**
 * Annotation defines a property as a relationship of JsonAPI-resource.
 *
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\Annotation
 */
class Relationship
{
    const TYPE_ONE  = 'one';
    const TYPE_MANY = 'many';

    /**
     * Name of resource inside of relationships-object.
     * By default will be used name of property.
     *
     * {
     *     "author": { <--- Name of relationship
     *         "data": {
     *             "id":   "12345",
     *             "type": "Author"
     *         }
     *     }
     * }
     *
     * @var string
     */
    public $name;

    /**
     * Type of relationship.
     *
     * @Enum({"one", "many"})
     *
     * @var string
     */
    public $type = self::TYPE_ONE;

    /**
     * Name of getter-method allows access to related object.
     * By default will be used "get{name-of-property}" method.
     *
     * @var string
     */
    public $getter;

    /**
     * Links belongs to relationship
     *
     * @var array<Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Link>
     */
    public $links = [];

    /**
     * Data-section with resource-identifier(s) allowed
     *
     * @var boolean
     */
    public $dataAllowed = false;

    /**
     * Limit of amount of resource-identifiers in data-section
     * Has a sense only with "x-to-may" type of relationship with allowed data
     *
     * @var int
     */
    public $dataLimit = 0;
}