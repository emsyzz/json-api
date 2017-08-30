<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation as JsonApi;

/**
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 *
 * @JsonApi\ResourceIdentifier(type="resource_type")
 *
 * @JsonApi\Link(
 *     name="definition_link",
 *     resource="repository_name.link_name",
 *     parameters={"param_name" = "param_value"},
 *     metadata={"meta_name" = "meta_value"}
 * )
 */
class Fixture
{
    /**
     * @JsonApi\Relationship(type="many", links={
     *     @JsonApi\Link(
     *         name="relation_link",
     *         resource="repository_name.link_name",
     *         parameters={"param_name" = "param_value"},
     *         metadata={"meta_name" = "meta_value"}
     *     )
     * }, dataAllowed=true, dataLimit=1000)
     */
    protected $test;

    /**
     * Get test
     */
    public function getTest()
    {

    }
}