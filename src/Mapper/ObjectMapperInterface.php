<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper;

use Mikemirten\Component\JsonApi\Document\ResourceObject;

/**
 * Interface of data mapper between an object and a document's resource
 *
 * @package Mikemirten\Component\JsonApi\ObjectTransformer
 */
interface ObjectMapperInterface
{
    /**
     * Transform object to a resource
     *
     * @param  mixed $object
     * @return ResourceObject
     */
    public function toResource($object): ResourceObject;

    /**
     * Map a resource to an object
     *
     * @param  mixed          $object
     * @param  ResourceObject $resource
     * @return mixed
     */
    public function fromResource($object, ResourceObject $resource);
}