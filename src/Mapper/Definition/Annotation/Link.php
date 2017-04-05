<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\Annotation;

/**
 * Link annotation
 *
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\Annotation
 */
class Link
{
    /**
     * Name of link inside of links-object of document or resource
     *
     * @Required
     *
     * @var string
     */
    public $name;

    /**
     * Reference to a repository's resource to resolve link
     * Format: "repository_name.link_name"
     *
     * @Required
     *
     * @var string
     */
    public $resource;

    /**
     * Parameters of link
     * [placeholder_name => path_to_get]
     *
     * @var array
     */
    public $parameters = [];

    /**
     * Additional metadata for link
     * [name => value]
     *
     * @var array
     */
    public $metadata = [];
}