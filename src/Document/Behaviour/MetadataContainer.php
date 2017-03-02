<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

/**
 * Metadata-container behaviour
 *
 * @see http://jsonapi.org/format/#document-meta
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
trait MetadataContainer
{
    /**
     * Metadata attributes
     *
     * @var array
     */
    protected $metadata = [];

    /**
     * Set attribute of metadata
     *
     * @param string $name
     * @param $value
     */
    public function setMetadataAttribute(string $name, $value)
    {
        $this->metadata[$name] = $value;
    }

    /**
     * Has attribute of metadata
     *
     * @param  string $name
     * @return bool
     */
    public function hasMetadataAttribute(string $name): bool
    {
        return array_key_exists($name, $this->metadata);
    }

    /**
     * Get attribute of metadata
     *
     * @param string $name
     * @return mixed
     */
    public function getMetadataAttribute(string $name)
    {
        return $this->metadata[$name];
    }

    /**
     * Get all attributes of metadata
     *
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}