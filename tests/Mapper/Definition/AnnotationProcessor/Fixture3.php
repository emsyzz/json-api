<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation as JsonApi;

/**
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class Fixture3
{
    /**
     * @JsonApi\Attribute(type="datetime(Y-m-d, 123)", many=true, processNull=true)
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