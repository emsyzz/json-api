<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Mikemirten\Component\JsonApi\Mapper\Definition\Relationship;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class DefinitionTest extends TestCase
{
    public function testEmptyAttributes()
    {
        $definition = new Definition();

        $this->assertSame([], $definition->getAttributes());
    }

    public function testEmptyRelationships()
    {
        $definition = new Definition();

        $this->assertSame([], $definition->getRelationships());
    }

    public function testRelationships()
    {
        $relationship = $this->createMock(Relationship::class);

        $relationship->expects($this->once())
            ->method('getName')
            ->willReturn('Test');

        $definition = new Definition();
        $definition->addRelationship($relationship);

        $this->assertSame(['Test' => $relationship], $definition->getRelationships());
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp ~Test~
     */
    public function testRelationshipsOverride()
    {
        $relationship = $this->createMock(Relationship::class);

        $relationship->expects($this->exactly(2))
            ->method('getName')
            ->willReturn('Test');

        $definition = new Definition();
        $definition->addRelationship($relationship);
        $definition->addRelationship($relationship);
    }
}