<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class SingleIdentifierRelationshipTest extends TestCase
{
    public function testIdentifier()
    {
        $identifier   = $this->createMock(ResourceIdentifierObject::class);
        $relationship = new SingleIdentifierRelationship($identifier);

        $this->assertSame($identifier, $relationship->getIdentifier());
    }

    public function testMetadata()
    {
        $identifier   = $this->createMock(ResourceIdentifierObject::class);
        $relationship = new SingleIdentifierRelationship($identifier, ['test' => 42]);

        $this->assertInstanceOf(MetadataAwareInterface::class, $relationship);

        $this->assertFalse($relationship->hasMetadataAttribute('qwerty'));
        $this->assertTrue($relationship->hasMetadataAttribute('test'));
        $this->assertSame(42, $relationship->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $relationship->getMetadata());
    }

    public function testLinks()
    {
        $identifier   = $this->createMock(ResourceIdentifierObject::class);
        $relationship = new SingleIdentifierRelationship($identifier);

        $this->assertInstanceOf(LinksAwareInterface::class, $relationship);

        $link = $this->createMock(LinkObject::class);
        $relationship->setLink('test', $link);

        $this->assertFalse($relationship->hasLink('qwerty'));
        $this->assertTrue($relationship->hasLink('test'));
        $this->assertSame($link, $relationship->getLink('test'));
        $this->assertSame(['test' => $link], $relationship->getLinks());
    }

    public function testToArrayIdentifier()
    {
        $identifier = $this->createMock(ResourceIdentifierObject::class);

        $identifier->expects($this->once())
            ->method('toArray')
            ->willReturn(['test' => 'qwerty']);

        $relationship = new SingleIdentifierRelationship($identifier);

        $this->assertSame(
            [
                'data' => ['test' => 'qwerty']
            ],
            $relationship->toArray()
        );
    }

    public function testToArrayMetadata()
    {
        $identifier   = $this->createMock(ResourceIdentifierObject::class);
        $relationship = new SingleIdentifierRelationship($identifier);
        $relationship->setMetadataAttribute('test', 'qwerty');

        $this->assertSame(
            [
                'meta' => ['test' => 'qwerty'],
                'data' => []
            ],
            $relationship->toArray()
        );
    }

    public function testToArrayLinks()
    {
        $identifier   = $this->createMock(ResourceIdentifierObject::class);
        $relationship = new SingleIdentifierRelationship($identifier);

        $link = $this->createMock(LinkObject::class);

        $link->method('getReference')
            ->willReturn('http://qwerty.com');

        $relationship->setLink('test', $link);

        $this->assertSame(
            [
                'links' => ['test' => 'http://qwerty.com'],
                'data'  => []
            ],
            $relationship->toArray()
        );
    }

    public function testToString()
    {
        $identifier   = $this->createMock(ResourceIdentifierObject::class);
        $relationship = new SingleIdentifierRelationship($identifier);

        $this->assertRegExp('~Relationship~', (string) $relationship);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeNotFoundException
     *
     * @expectedExceptionMessageRegExp ~Relationship~
     * @expectedExceptionMessageRegExp ~test_attribute~
     */
    public function testMetadataNotFound()
    {
        $identifier   = $this->createMock(ResourceIdentifierObject::class);
        $relationship = new SingleIdentifierRelationship($identifier);

        $relationship->getMetadataAttribute('test_attribute');
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeOverrideException
     *
     * @expectedExceptionMessageRegExp ~Relationship~
     * @expectedExceptionMessageRegExp ~test_attribute~
     */
    public function testMetadataOverride()
    {
        $identifier   = $this->createMock(ResourceIdentifierObject::class);
        $relationship = new SingleIdentifierRelationship($identifier);

        $relationship->setMetadataAttribute('test_attribute', 1);
        $relationship->setMetadataAttribute('test_attribute', 2);
    }
}