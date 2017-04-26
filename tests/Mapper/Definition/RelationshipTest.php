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

    public function testPropertyName()
    {
        $relationship = new Relationship('test', Relationship::TYPE_X_TO_ONE);

        $this->assertFalse($relationship->hasPropertyName());

        $relationship->setPropertyName('qwerty');

        $this->assertTrue($relationship->hasPropertyName());
        $this->assertSame('qwerty', $relationship->getPropertyName());
    }

    public function testGetter()
    {
        $relationship = new Relationship('test', Relationship::TYPE_X_TO_ONE);

        $this->assertFalse($relationship->hasGetter());

        $relationship->setGetter('qwerty');

        $this->assertTrue($relationship->hasGetter());
        $this->assertSame('qwerty', $relationship->getGetter());
    }

    public function testDataIncluded()
    {
        $relationship = new Relationship('test', Relationship::TYPE_X_TO_ONE);

        $this->assertFalse($relationship->isDataIncluded());

        $relationship->setIncludeData();

        $this->assertTrue($relationship->isDataIncluded());
    }

    public function testDataLimit()
    {
        $relationship = new Relationship('test', Relationship::TYPE_X_TO_ONE);

        $this->assertSame(0, $relationship->getDataLimit());

        $relationship->setDataLimit(1000);

        $this->assertSame(1000, $relationship->getDataLimit());
    }

    public function testTypeMerge()
    {
        $extraRelationship = $this->createMock(Relationship::class);

        $extraRelationship->expects($this->once())
            ->method('getType')
            ->willReturn(Relationship::TYPE_X_TO_MANY);

        $relationship = new Relationship('test', Relationship::TYPE_X_TO_ONE);
        $relationship->merge($extraRelationship);

        $this->assertSame(Relationship::TYPE_X_TO_MANY, $relationship->getType());
    }

    public function testMergeLinks()
    {
        $extraLink1 = $this->createMock(Link::class);
        $extraLink2 = $this->createMock(Link::class);

        $extraRelationship = $this->createMock(Relationship::class);

        $extraRelationship->expects($this->once())
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

        $relationship = new Relationship('test', Relationship::TYPE_X_TO_ONE);
        $relationship->addLink($link);
        $relationship->merge($extraRelationship);

        $this->assertSame(
            [
                'test_link'  => $link,
                'test_link2' => $extraLink2
            ],
            $relationship->getLinks()
        );
    }
}