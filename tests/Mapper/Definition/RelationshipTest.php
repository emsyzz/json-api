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

    public function testIdGetter()
    {
        $relationship = new Relationship('test', Relationship::TYPE_X_TO_ONE);

        $this->assertFalse($relationship->hasIdentifierGetter());

        $relationship->setIdentifierGetter('qwerty');

        $this->assertTrue($relationship->hasIdentifierGetter());
        $this->assertSame('qwerty', $relationship->getIdentifierGetter());
    }

    public function testResourceType()
    {
        $relationship = new Relationship('test', Relationship::TYPE_X_TO_ONE);

        $this->assertFalse($relationship->hasResourceType());

        $relationship->setResourceType('qwerty');

        $this->assertTrue($relationship->hasResourceType());
        $this->assertSame('qwerty', $relationship->getResourceType());
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
}