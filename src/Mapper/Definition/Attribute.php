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
     * Name of property contains related object.
     * Value is optional. Can be set only for real properties.
     *
     * @var string
     */
    protected $propertyName;

    /**
     * Unique name of serialized attribute
     *
     * @var string
     */
    protected $name;

    /**
     * Data-type
     *
     * @var string
     */
    protected $type;

    /**
     * Attribute is an iterable container of values
     *
     * @var bool
     */
    protected $many;

    /**
     * Parameters for data-type handler
     *
     * @var array
     */
    protected $typeParameters = [];

    /**
     * Getter-method to access value
     *
     * @var string
     */
    protected $getter;

    /**
     * Setter-method to access value
     *
     * @var string
     */
    protected $setter;

    /**
     * Process null-values:
     *  - add to resource attribute with null-value
     *  - set to object null value from resources
     *
     * @var bool
     */
    protected $processNull;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param string $getter
     */
    public function __construct(string $name, string $getter)
    {
        $this->name   = $name;
        $this->getter = $getter;
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
     * Get name of getter-method to access value of attribute
     *
     * @return string
     */
    public function getGetter(): string
    {
        return $this->getter;
    }

    /**
     * Set name of setter-method to access value of attribute
     *
     * @param string $name
     */
    public function setSetter(string $name)
    {
        $this->setter = $name;
    }

    /**
     * Has setter-method defined ?
     *
     * @return bool
     */
    public function hasSetter(): bool
    {
        return $this->setter !== null;
    }

    /**
     * Get name of setter-method to access value of attribute
     *
     * @return string
     */
    public function getSetter(): string
    {
        return $this->setter;
    }

    /**
     * Set flag "processNull"
     *
     * @param bool $process
     */
    public function setProcessNull($process = true)
    {
        $this->processNull = $process;
    }

    /**
     * Get value of "processNull" flag
     *
     * @return bool
     */
    public function getProcessNull(): bool
    {
        return $this->processNull !== null && $this->processNull;
    }

    /**
     * Has flag "processNull" defined
     *
     * @return bool
     */
    public function hasProcessNull(): bool
    {
        return $this->processNull !== null;
    }

    /**
     * Set name of data-type
     *
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Has data-type defined ?
     *
     * @return bool
     */
    public function hasType(): bool
    {
        return $this->type !== null;
    }

    /**
     * Get name of data-type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set "many" flag
     *
     * @param bool $many
     */
    public function setMany($many = true)
    {
        $this->many = $many;
    }

    /**
     * Attribute is an iterable container of values ?
     *
     * @return bool
     */
    public function isMany(): bool
    {
        return $this->many !== null && $this->many;
    }

    /**
     * Flag "many" has been defined
     *
     * @return bool
     */
    public function hasManyDefined(): bool
    {
        return $this->many !== null;
    }

    /**
     * Set parameters of data-type handling
     *
     * @param array $parameters
     */
    public function setTypeParameters(array $parameters)
    {
        $this->typeParameters = $parameters;
    }

    /**
     * Get parameters of data-type handling
     *
     * @return array
     */
    public function getTypeParameters(): array
    {
        return $this->typeParameters;
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
     * Merge a attribute into this one
     *
     * @param self $attribute
     */
    public function merge(self $attribute)
    {
        if ($this->propertyName === null && $attribute->hasPropertyName()) {
            $this->propertyName = $attribute->getPropertyName();
        }

        if ($this->type === null && $attribute->hasType()) {
            $this->type = $attribute->getType();
        }

        if ($this->many === null && $attribute->hasManyDefined()) {
            $this->many = $attribute->isMany();
        }

        if (empty($this->typeParameters)) {
            $this->typeParameters = $attribute->getTypeParameters();
        }

        if ($this->processNull === null && $attribute->hasProcessNull()) {
            $this->processNull = $attribute->getProcessNull();
        }
    }
}