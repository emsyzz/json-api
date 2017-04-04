<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

/**
 * Link definition
 *
 * @package Mapper\Definition
 */
class Link
{
    /**
     * Unique name of link-object inside of document or resource
     *
     * @var string
     */
    protected $name;

    /**
     * Name of repository supposed to contain link
     *
     * @var string
     */
    protected $repositoryName;

    /**
     * Name of resource inside of repository
     *
     * @var string
     */
    protected $resourceName;

    /**
     * Parameters for link resolving
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Additional metadata
     *
     * @var array
     */
    protected $metadata = [];

    /**
     * Link constructor.
     *
     * @param string $name
     * @param string $repositoryName
     * @param string $linkName
     */
    public function __construct(string $name, string $repositoryName, string $linkName)
    {
        $this->name           = $name;
        $this->repositoryName = $repositoryName;
        $this->linkName       = $linkName;
    }

    /**
     * Get unique name of link-object inside of document or resource
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get name of repository supposed to contain link
     *
     * @return string
     */
    public function getRepositoryName(): string
    {
        return $this->repositoryName;
    }

    /**
     * Get name of link inside of repository
     *
     * @return string
     */
    public function getLinkName(): string
    {
        return $this->linkName;
    }

    /**
     * Set parameters for link resolving (overwrites existing ones)
     *
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Get parameters for link resolving
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Set metadata (overwrites existing one)
     *
     * @param array $metadata
     */
    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Get metadata
     *
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}