<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation as JsonApi;

/**
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class Fixture3
{
    /**
     * @JsonApi\Attribute(type="datetime(Y-m-d, 123)", many=true)
     */
    protected $test;

    /**
     * Get test
     */
    public function getTest()
    {

    }

    /**
     * Set test
     */
    public function setTest()
    {

    }
}