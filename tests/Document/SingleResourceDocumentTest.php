<?php

namespace Mikemirten\Component\JsonApi\Document;

use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class SingleResourceDocumentTest extends TestCase
{
    public function testResource()
    {
        $resource = $this->createMock(ResourceObject::class);
        $document = new SingleResourceDocument($resource);

        $this->assertSame($resource, $document->getResource());
    }

    public function testMetadata()
    {
        $resource = $this->createMock(ResourceObject::class);
        $document = new SingleResourceDocument($resource, ['test' => 42]);

        $this->assertFalse($document->hasMetadataAttribute('qwerty'));
        $this->assertTrue($document->hasMetadataAttribute('test'));
        $this->assertSame(42, $document->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $document->getMetadata());
    }

    public function testLinks()
    {
        $link = $this->createMock(LinkObject::class);

        $resource = $this->createMock(ResourceObject::class);
        $document = new SingleResourceDocument($resource);
        $document->setLink('test', $link);

        $this->assertFalse($document->hasLink('qwerty'));
        $this->assertTrue($document->hasLink('test'));
        $this->assertSame($link, $document->getLink('test'));
        $this->assertSame(['test' => $link], $document->getLinks());
    }
}