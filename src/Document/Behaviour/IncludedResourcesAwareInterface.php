<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

use Mikemirten\Component\JsonApi\Document\ResourceObject;

/**
 * Interface of an object aware of included resources
 *
 * @see http://jsonapi.org/format/#document-compound-documents
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
interface IncludedResourcesAwareInterface
{
    /**
     * Add included resource
     *
     * @param  ResourceObject $resource
     * @return mixed
     */
    public function addIncludedResource(ResourceObject $resource);

    /**
     * Get all included resources
     *
     * @return ResourceObject[]
     */
    public function getIncludedResources(): array;

    /**
     * Contains any included resources ?
     *
     * @return bool
     */
    public function hasIncludedResources(): bool;
}