<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\Annotation;

/**
 * Annotation defines a property as a relationship of JsonAPI-resource.
 *
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * @package Mapper\Definition\Annotation
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
     * Type of resource.
     * By default a type-handler supposed to be used.
     *
     * @var string
     */
    public $resourceType;

    /**
     * Name of getter-method allows access to related object.
     * By default will be used "get{name-of-property}" method.
     *
     * @var string
     */
    public $getter;

    /**
     * Name of property of related object, contain identifier
     *
     * @var string
     */
    public $idProperty;

    /**
     * Name of getter-method allows access to an identifier of related object (each object for a collection).
     * By default, if identifier property has been defined, will be used "get{name-of-property}" method,
     * otherwise an identifier handler supposed to be used.
     *
     * @var string
     */
    public $idGetter;
}