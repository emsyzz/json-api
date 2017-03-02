<?php
declare(strict_types = 1);

namespace Mikemirten\JsonApi\Component\Document\Behaviour;

/**
 * Attributes-container behaviour
 *
 * @see http://jsonapi.org/format/#document-resource-object-attributes
 *
 * @package Mikemirten\JsonApi\Component\Document\Behaviour
 */
trait AttributesContainer
{
    /**
     * Attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Set attribute
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Has attribute
     *
     * @param  string $name
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * Get attribute
     *
     * @param  string $name
     * @return mixed
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name];
    }

    /**
     * Get all attributes
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}