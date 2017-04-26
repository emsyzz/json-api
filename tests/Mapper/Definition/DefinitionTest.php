<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class DefinitionTest extends TestCase
{
    public function testClass()
    {
        $definition = new Definition('stdClass');

        $this->assertSame('stdClass', $definition->getClass());
    }

    public function testEmptyAttributes()
    {
        $definition = new Definition('stdClass');

        $this->assertSame([], $definition->getAttributes());
    }

    public function testEmptyRelationships()
    {
        $definition = new Definition('stdClass');

        $this->assertSame([], $definition->getRelationships());
    }

    public function testRelationships()
    {
        $relationship = $this->createMock(Relationship::class);

        $relationship->expects($this->once())
            ->method('getName')
            ->willReturn('Test');

        $definition = new Definition('stdClass');
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

        $definition = new Definition('stdClass');
        $definition->addRelationship($relationship);
        $definition->addRelationship($relationship);
    }

    public function testLinks()
    {
        $link = $this->createMock(Link::class);

        $link->expects($this->once())
            ->method('getName')
            ->willReturn('test');

        $definition = new Definition('stdClass');
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

        $definition = new Definition('stdClass');
        $definition->addLink($link);
        $definition->addLink($link);
    }

    public function testType()
    {
        $definition = new Definition('stdClass');

        $this->assertFalse($definition->hasType());

        $definition->setType('qwerty');

        $this->assertTrue($definition->hasType());
        $this->assertSame('qwerty', $definition->getType());
    }

    public function testMergeType()
    {
        $extraDefinition = $this->createMock(Definition::class);

        $extraDefinition->method('hasType')
            ->willReturn(true);

        $extraDefinition->expects($this->once())
            ->method('getType')
            ->willReturn('TestType');

        $definition = new Definition('stdClass');
        $definition->merge($extraDefinition);

        $this->assertSame('TestType', $definition->getType());
    }

    /**
     * @depends testLinks
     */
    public function testMergeLinks()
    {
        $extraLink1 = $this->createMock(Link::class);
        $extraLink2 = $this->createMock(Link::class);

        $extraDefinition = $this->createMock(Definition::class);

        $extraDefinition->expects($this->once())
            ->method('getLinks')
            ->willReturn([
                'test_link'  => $extraLink1,
                'test_link2' => $extraLink2
            ]);

        $link = $this->createMock(Link::class);

        $link->method('getName')
            ->willReturn('test_link');

        $link->expects($this->once())
            ->method('merge')
            ->with($extraLink1);

        $definition = new Definition('stdClass');
        $definition->addLink($link);
        $definition->merge($extraDefinition);

        $this->assertSame(
            [
                'test_link'  => $link,
                'test_link2' => $extraLink2
            ],
            $definition->getLinks()
        );
    }

    public function testMergeAttributes()
    {
        $extraAttribute1 = $this->createMock(Attribute::class);
        $extraAttribute2 = $this->createMock(Attribute::class);

        $extraDefinition = $this->createMock(Definition::class);

        $extraDefinition->expects($this->once())
            ->method('getAttributes')
            ->willReturn([
                'test_attr'  => $extraAttribute1,
                'test_attr2' => $extraAttribute2
            ]);

        $attribute = $this->createMock(Attribute::class);

        $attribute->method('getName')
            ->willReturn('test_attr');

        $attribute->expects($this->once())
            ->method('merge')
            ->with($extraAttribute1);

        $definition = new Definition('stdClass');
        $definition->addAttribute($attribute);
        $definition->merge($extraDefinition);

        $this->assertSame(
            [
                'test_attr'  => $attribute,
                'test_attr2' => $extraAttribute2
            ],
            $definition->getAttributes()
        );
    }

    public function testMergeRelationships()
    {
        $extraRelationship1 = $this->createMock(Relationship::class);
        $extraRelationship2 = $this->createMock(Relationship::class);

        $extraDefinition = $this->createMock(Definition::class);

        $extraDefinition->expects($this->once())
            ->method('getRelationships')
            ->willReturn([
                'test_rel'  => $extraRelationship1,
                'test_rel2' => $extraRelationship2
            ]);

        $relationship = $this->createMock(Relationship::class);

        $relationship->method('getName')
            ->willReturn('test_rel');

        $relationship->expects($this->once())
            ->method('merge')
            ->with($extraRelationship1);

        $definition = new Definition('stdClass');
        $definition->addRelationship($relationship);
        $definition->merge($extraDefinition);

        $this->assertSame(
            [
                'test_rel'  => $relationship,
                'test_rel2' => $extraRelationship2
            ],
            $definition->getRelationships()
        );
    }
}