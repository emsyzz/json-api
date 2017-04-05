<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation as JsonApi;

/**
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
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
     * @JsonApi\Relationship(type="many", resourceType="Fixture", idProperty="id", links={
     *     @JsonApi\Link(
     *         name="relation_link",
     *         resource="repository_name.link_name",
     *         parameters={"param_name" = "param_value"},
     *         metadata={"meta_name" = "meta_value"}
     *     )
     * })
     */
    protected $test;
}