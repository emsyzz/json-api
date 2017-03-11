<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\RelationshipsAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class ResourceObjectTest extends TestCase
{
    public function testBasics()
    {
        $resource = new ResourceObject('42', 'test');

        $this->assertSame('42', $resource->getId());
        $this->assertSame('test', $resource->getType());
    }

    public function testAttributes()
    {
        $resource = new ResourceObject('42', 'test', [
            'test' => 42
        ]);

        $this->assertFalse($resource->hasAttribute('qwerty'));
        $this->assertTrue($resource->hasAttribute('test'));
        $this->assertSame(42, $resource->getAttribute('test'));
        $this->assertSame(['test' => 42], $resource->getAttributes());
    }

    public function testMetadata()
    {
        $resource = new ResourceObject('42', 'test', [], [
            'test' => 42
        ]);

        $this->assertInstanceOf(MetadataAwareInterface::class, $resource);

        $this->assertFalse($resource->hasMetadataAttribute('qwerty'));
        $this->assertTrue($resource->hasMetadataAttribute('test'));
        $this->assertSame(42, $resource->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $resource->getMetadata());
    }

    public function testLinks()
    {
        $resource = new ResourceObject('42', 'test');

        $this->assertInstanceOf(LinksAwareInterface::class, $resource);

        $link = $this->createMock(LinkObject::class);
        $resource->setLink('test', $link);

        $this->assertFalse($resource->hasLink('qwerty'));
        $this->assertTrue($resource->hasLink('test'));
        $this->assertSame($link, $resource->getLink('test'));
        $this->assertSame(['test' => $link], $resource->getLinks());
    }

    public function testRelationships()
    {
        $resource = new ResourceObject('42', 'test');

        $this->assertInstanceOf(RelationshipsAwareInterface::class, $resource);

        $relationship = $this->createMock(AbstractRelationship::class);
        $resource->setRelationship('test', $relationship);

        $this->assertFalse($resource->hasRelationship('qwerty'));
        $this->assertTrue($resource->hasRelationship('test'));
        $this->assertSame($relationship, $resource->getRelationship('test'));
        $this->assertSame(['test' => $relationship], $resource->getRelationships());
    }

    public function testToArrayBasics()
    {
        $resource = new ResourceObject('42', 'test');

        $this->assertSame(
            [
                'id'   => '42',
                'type' => 'test'
            ],
            $resource->toArray()
        );
    }

    public function testToArrayAttributes()
    {
        $resource = new ResourceObject('42', 'test');
        $resource->setAttribute('test_attr', 'qwerty');

        $this->assertSame(
            ['test_attr' => 'qwerty'],
            $resource->toArray()['attributes']
        );
    }

    public function testToArrayRelationships()
    {
        $relationship = $this->createMock(AbstractRelationship::class);

        $relationship->expects($this->once())
            ->method('toArray')
            ->willReturn(['test' => 'qwerty']);

        $resource = new ResourceObject('42', 'test');
        $resource->setRelationship('test_rel', $relationship);

        $this->assertSame(
            ['test_rel' => ['test' => 'qwerty']],
            $resource->toArray()['relationships']
        );
    }

    public function testToArrayMetadata()
    {
        $resource = new ResourceObject('42', 'test');
        $resource->setMetadataAttribute('test_attr', 'qwerty');

        $this->assertSame(
            ['test_attr' => 'qwerty'],
            $resource->toArray()['meta']
        );
    }

    public function testToArrayLinks()
    {
        $link = $this->createMock(LinkObject::class);

        $link->expects($this->once())
            ->method('getReference')
            ->willReturn('http://qwerty.com');

        $resource = new ResourceObject('42', 'test');
        $resource->setLink('test_link', $link);

        $this->assertSame(
            ['test_link' => 'http://qwerty.com'],
            $resource->toArray()['links']
        );
    }
}