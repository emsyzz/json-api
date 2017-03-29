<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Document\ResourceObject;

/**
 * Interface of mapping handler.
 * Each handler responsible for certain part of resource (links, metadata, relationships...).
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
interface HandlerInterface
{
    /**
     * Transfer data from object to resource
     *
     * @param  mixed          $object
     * @param  ResourceObject $resource
     */
    public function toResource($object, ResourceObject $resource);

    /**
     * Transfer data from resource to object
     *
     * @param  mixed          $object
     * @param  ResourceObject $resource
     */
    public function fromResource($object, ResourceObject $resource);
}