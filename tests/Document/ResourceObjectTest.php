<?php

namespace Mikemirten\Component\JsonApi\Document;

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

        $this->assertFalse($resource->hasMetadataAttribute('qwerty'));
        $this->assertTrue($resource->hasMetadataAttribute('test'));
        $this->assertSame(42, $resource->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $resource->getMetadata());
    }

    public function testLinks()
    {
        $link = $this->createMock(LinkObject::class);

        $resource = new ResourceObject('42', 'test');
        $resource->setLink('test', $link);

        $this->assertFalse($resource->hasLink('qwerty'));
        $this->assertTrue($resource->hasLink('test'));
        $this->assertSame($link, $resource->getLink('test'));
        $this->assertSame(['test' => $link], $resource->getLinks());
    }
}