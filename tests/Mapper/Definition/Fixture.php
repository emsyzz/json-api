<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation as JsonApi;

class Fixture
{
    /**
     * @JsonApi\Relationship(type="many", resourceType="Fixture", idProperty="id")
     */
    protected $test;
}