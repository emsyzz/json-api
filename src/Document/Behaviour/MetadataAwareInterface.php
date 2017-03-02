<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

/**
 * Interface of an object aware of metadata
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
interface MetadataAwareInterface
{
    /**
     * Set attribute of metadata
     *
     * @param string $name
     * @param $value
     */
    public function setMetadataAttribute(string $name, $value);

    /**
     * Has attribute of metadata
     *
     * @param  string $name
     * @return bool
     */
    public function hasMetadataAttribute(string $name): bool;

    /**
     * Get attribute of metadata
     *
     * @param string $name
     * @return mixed
     */
    public function getMetadataAttribute(string $name);

    /**
     * Get all attributes of metadata
     *
     * @return array
     */
    public function getMetadata(): array;
}