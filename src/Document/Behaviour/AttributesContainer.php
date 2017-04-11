<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

use Mikemirten\Component\JsonApi\Exception\AttributeNotFoundException;
use Mikemirten\Component\JsonApi\Exception\AttributeOverrideException;

/**
 * Attributes-container behaviour
 *
 * @see http://jsonapi.org/format/#document-resource-object-attributes
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
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
     * @param  string $name
     * @param  mixed  $value
     * @throws AttributeOverrideException
     */
    public function setAttribute(string $name, $value)
    {
        if (isset($this->attributes[$name])) {
            throw new AttributeOverrideException($this, $name);
        }

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
     * @throws AttributeNotFoundException
     */
    public function getAttribute(string $name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        throw new AttributeNotFoundException($this, $name);
    }

    /**
     * Contains any attributes ?
     *
     * @return bool
     */
    public function hasAttributes(): bool
    {
        return count($this->attributes) > 0;
    }

    /**
     * Remove attribute
     *
     * @param string $name
     */
    public function removeAttribute(string $name)
    {
        unset($this->attributes[$name]);
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