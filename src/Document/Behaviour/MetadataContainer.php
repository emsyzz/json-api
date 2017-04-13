<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;
use Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeNotFoundException;
use Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeOverrideException;

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
        if (array_key_exists($name, $this->metadata)) {
            throw new MetadataAttributeOverrideException($this, $name);
        }

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
        if (array_key_exists($name, $this->metadata)) {
            return $this->metadata[$name];
        }

        throw new MetadataAttributeNotFoundException($this, $name);
    }

    /**
     * Contains any metadata ?
     *
     * @return bool
     */
    public function hasMetadata(): bool
    {
        return count($this->metadata) > 0;
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

    /**
     * Remove attribute of metadata
     *
     * @param string $name
     */
    public function removeMetadataAttribute(string $name)
    {
        unset($this->metadata[$name]);
    }
}