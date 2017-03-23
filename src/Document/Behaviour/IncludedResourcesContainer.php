<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

use Mikemirten\Component\JsonApi\Document\ResourceObject;

/**
 * Collection of included resources container behaviour
 *
 * @see http://jsonapi.org/format/#document-compound-documents
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
trait IncludedResourcesContainer
{
    /**
     * Included resources
     *
     * @var ResourceObject[]
     */
    protected $includedResources = [];

    /**
     * Add included resource
     *
     * @param  ResourceObject $resource
     * @return mixed
     */
    public function addIncludedResource(ResourceObject $resource)
    {
        $this->includedResources[] = $resource;
    }

    /**
     * Get all included resources
     *
     * @return ResourceObject[]
     */
    public function getIncludedResources(): array
    {
        return $this->includedResources;
    }

    /**
     * Contains any included resources ?
     *
     * @return bool
     */
    public function hasIncludedResources(): bool
    {
        return count($this->includedResources) > 0;
    }

    /**
     * Cast included resources to an array
     *
     * @return array
     */
    protected function includedResourcesToArray(): array
    {
        $data = [];

        foreach ($this->includedResources as $resource)
        {
            $data[] = $resource->toArray();
        }

        return $data;
    }
}