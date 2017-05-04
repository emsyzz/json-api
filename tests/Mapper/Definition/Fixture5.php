<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation as JsonApi;

trait FixtureTrait
{
    /**
     * @JsonApi\Attribute(type="datetime(Y-m-d, 123)")
     */
    protected $test;

    /**
     * Get test
     */
    public function getTest()
    {

    }
}

class Fixture5
{
    use FixtureTrait;
}