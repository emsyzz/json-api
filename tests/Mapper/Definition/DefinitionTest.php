<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

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

    public function testLinks()
    {
        $link = $this->createMock(Link::class);

        $link->expects($this->once())
            ->method('getName')
            ->willReturn('test');

        $definition = new Definition();
        $definition->addLink($link);

        $this->assertSame($link, $definition->getLinks()['test']);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp ~test_link~
     */
    public function testLinksOverride()
    {
        $link = $this->createMock(Link::class);

        $link->expects($this->exactly(2))
            ->method('getName')
            ->willReturn('test_link');

        $definition = new Definition();
        $definition->addLink($link);
        $definition->addLink($link);
    }
}