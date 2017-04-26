<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Mikemirten\Component\JsonApi\Mapper\Definition\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Mapper\Definition\Behaviour\LinksContainer;


/**
 * Definition of relationship
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class Relationship implements LinksAwareInterface
{
    use LinksContainer;

    const TYPE_X_TO_ONE  = 1;
    const TYPE_X_TO_MANY = 2;

    /**
     * Name unique name of resource-object inside of relationships-object
     *
     * {
     *     "author": { <--- Name of resource-object
     *         "data": {
     *             "id":   "12345",
     *             "type": "Author"
     *         }
     *     }
     * }
     *
     * @var string
     */
    protected $name;

    /**
     * Type or relationship: "x to one" or "x to many".
     *
     * @see for TYPE_* constants.
     *
     * @var int
     */
    protected $type;

    /**
     * Extra metadata
     *
     * @var array
     */
    protected $metadata = [];

    /**
     * Name of property contains related object.
     * Value is optional. Can be set only for real properties.
     *
     * @var string
     */
    protected $propertyName;

    /**
     * Getter-method to access related data
     *
     * @var string
     */
    protected $getter;

    /**
     * Include data with resource-identifier(s)
     *
     * @var bool
     */
    protected $includeData = false;

    /**
     * Limit amount of resource identifiers of collection
     * Works only with "x-to-many" type of relation.
     *
     * @var int
     */
    protected $dataLimit = 0;

    /**
     * Relationship constructor.
     *
     * @param string $name
     * @param int    $type
     */
    public function __construct(string $name, int $type)
    {
        $this->name = $name;
        $this->type = $type;
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
     * Get metadata to include into document's relationship
     * [name => value]
     *
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Is x-to-many type of relationship ?
     *
     * @return bool
     */
    public function isCollection(): bool
    {
        return $this->type === self::TYPE_X_TO_MANY;
    }

    /**
     * Get type of relationship
     * @see TYPE_X_TO_* constants
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Set name of property contains related object
     *
     * @param string $name
     */
    public function setPropertyName(string $name)
    {
        $this->propertyName = $name;
    }

    /**
     * Has name of property ?
     *
     * @return bool
     */
    public function hasPropertyName(): bool
    {
        return $this->propertyName !== null;
    }

    /**
     * Get name of property contains related object
     *
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * Set name of a getter-method to access related data
     *
     * @param string $method
     */
    public function setGetter(string $method)
    {
        $this->getter = $method;
    }

    /**
     * Contains name of a getter-method to access related data ?
     *
     * @return bool
     */
    public function hasGetter(): bool
    {
        return $this->getter !== null;
    }

    /**
     * Get name of a getter-method to access related data
     *
     * @return string
     */
    public function getGetter(): string
    {
        return $this->getter;
    }

    /**
     * Set include-data option
     *
     * @param bool $include
     */
    public function setIncludeData(bool $include = true)
    {
        $this->includeData = $include;
    }

    /**
     * Is data-section with resource-identifier(s) have to be included ?
     *
     * @return bool
     */
    public function isDataIncluded(): bool
    {
        return $this->includeData;
    }

    /**
     * Set limit of amount of resource-objects in data-section
     * Works only for "x-to-many" type of relationship with included data allowed
     *
     * @param int $limit
     */
    public function setDataLimit(int $limit)
    {
        $this->dataLimit = $limit;
    }

    /**
     * Get limit of amount of resource-objects in data-section
     * Has a sense only for "x-to-many" type of relationship with included data allowed
     *
     * @return int
     */
    public function getDataLimit(): int
    {
        return $this->dataLimit;
    }

    /**
     * Merge a relationship into this one
     * Named data of given relationship will override existing one in the case of names conflict
     *
     * @param self $relationship
     */
    public function merge(self $relationship)
    {
        $this->type = $relationship->getType();

        $this->mergeLinks($relationship);
    }
}