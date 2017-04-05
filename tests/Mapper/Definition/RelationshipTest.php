<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class RelationshipTest extends TestCase
{
    public function testBasics()
    {
        $relationship = new Relationship('rel_name', Relationship::TYPE_X_TO_MANY);

        $this->assertSame('rel_name', $relationship->getName());
        $this->assertTrue($relationship->isCollection());
    }
}