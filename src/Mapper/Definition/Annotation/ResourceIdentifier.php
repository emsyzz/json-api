<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\Annotation;

/**
 * Class Resource
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\Annotation
 */
class ResourceIdentifier
{
    /**
     * Type of resource
     *
     * @var string
     */
    public $type;
}