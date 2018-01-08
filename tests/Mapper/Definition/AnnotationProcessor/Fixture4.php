<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation as JsonApi;

/**
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class Fixture4
{
    /**
     * @JsonApi\Attribute(type="datetime(Y-m-d, 123)", setter="setTest")
     */
    public function getTest()
    {

    }
}