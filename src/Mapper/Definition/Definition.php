<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Mikemirten\Component\JsonApi\Mapper\Definition\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Mapper\Definition\Behaviour\LinksContainer;

/**
 * Mapping Definition
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class Definition implements LinksAwareInterface
{
    use LinksContainer;

    /**
     * Class covered by definition
     *
     * @var string
     */
    protected $class;

    /**
     * Resource type
     *
     * @var string
     */
    protected $type;

    /**
     * Attributes
     *
     * @var Attribute[]
     */
    protected $attributes = [];

    /**
     * Relationships
     *
     * @var Relationship[]
     */
    protected $relationships = [];

    /**
     * Definition constructor.
     *
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * Get class covered by definition
     *
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Set type of resource
     *
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Has type of resource defined ?
     *
     * @return bool
     */
    public function hasType(): bool
    {
        return $this->type !== null;
    }

    /**
     * Get type of resource
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Add attribute
     *
     * @param  Attribute $attribute
     * @throws \LogicException
     */
    public function addAttribute(Attribute $attribute)
    {
        $name = $attribute->getName();

        if (isset($this->attributes[$name])) {
            throw new \LogicException(sprintf('Attribute "%s" already defined.', $name));
        }

        $this->attributes[$name] = $attribute;
    }

    /**
     * Get attributes
     *
     * @return Attribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Add relationship
     *
     * @param  Relationship $relationship
     * @throws \LogicException
     */
    public function addRelationship(Relationship $relationship)
    {
        $name = $relationship->getName();

        if (isset($this->relationships[$name])) {
            throw new \LogicException(sprintf('Relationship "%s" already defined.', $name));
        }

        $this->relationships[$name] = $relationship;
    }

    /**
     * Get relationships
     *
     * @return Relationship[]
     */
    public function getRelationships(): array
    {
        return $this->relationships;
    }
}