<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

/**
 * Definition of relationship
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class Relationship
{
    const TYPE_X_TO_ONE  = 1;
    const TYPE_X_TO_MANY = 2;

    /**
     * Unique name
     *
     * @var string
     */
    protected $name;

    /**
     * Type
     *
     * @var int
     */
    protected $relationType;

    /**
     * Collection of links
     *
     * @var array
     */
    protected $links = [];

    /**
     * Extra metadata
     *
     * @var array
     */
    protected $metadata = [];

    /**
     * Getter-method to access related data
     *
     * @var string
     */
    protected $getter;

    /**
     * Getter-method to access an identifier of related object
     *
     * @var string
     */
    protected $identifierGetter;

    /**
     * Type of related resource
     *
     * @var string
     */
    protected $resourceType;

    /**
     * Relationship constructor.
     *
     * @param string $name
     * @param string $resourceType
     * @param int    $relationType
     */
    public function __construct(string $name, string $resourceType, int $relationType)
    {
        $this->name         = $name;
        $this->resourceType = $resourceType;
        $this->relationType = $relationType;
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
     * Set link
     *
     * @param string        $name
     * @param LinkInterface $link
     */
    public function setLink(string $name, LinkInterface $link)
    {
        if (isset($this->links[$name])) {
            throw new \LogicException(sprintf('A link name by "%s" is already exists for the relationship'));
        }

        $this->links[$name] = $link;
    }

    /**
     * Get links
     * [name => link-object]
     *
     * @return LinkInterface[]
     */
    public function getLinks(): array
    {
        $this->links;
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
        return $this->relationType === self::TYPE_X_TO_MANY;
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
     * Get name of a getter-method to access related data
     *
     * @return string
     */
    public function getGetter(): string
    {
        return $this->getter;
    }

    /**
     * Set getter-method to access an identifier of related object
     *
     * @param string $method
     */
    public function setIdentifierGetter(string $method)
    {
        $this->identifierGetter = $method;
    }

    /**
     * Get getter-method to access an identifier of related object
     *
     * @return string
     */
    public function getIdentifierGetter(): string
    {
        return $this->identifierGetter;
    }

    /**
     * Get type of resource
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return $this->resourceType;
    }
}