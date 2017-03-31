<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;

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
     * @param  MappingContext $context
     */
    public function toResource($object, ResourceObject $resource, MappingContext $context);

    /**
     * Transfer data from resource to object
     *
     * @param  mixed          $object
     * @param  ResourceObject $resource
     * @param  MappingContext $context
     */
    public function fromResource($object, ResourceObject $resource, MappingContext $context);
}