<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

class ResourceCollectionDocumentTest extends TestCase
{
    public function testResources()
    {
        $document = new ResourceCollectionDocument();

        $this->assertEmpty($document->getResources());

        $resource = $this->createMock(ResourceObject::class);
        $document->addResource($resource);

        $this->assertSame($resource, $document->getFirstResource());
        $this->assertSame([$resource], $document->getResources());
    }

    public function testIterator()
    {
        $document = new ResourceCollectionDocument();
        $resource = $this->createMock(ResourceObject::class);
        $document->addResource($resource);

        $this->assertSame([$resource], iterator_to_array($document));
    }

    public function testMetadata()
    {
        $document = new ResourceCollectionDocument(['test' => 42]);

        $this->assertInstanceOf(MetadataAwareInterface::class, $document);

        $this->assertFalse($document->hasMetadataAttribute('qwerty'));
        $this->assertTrue($document->hasMetadataAttribute('test'));
        $this->assertSame(42, $document->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $document->getMetadata());
    }

    public function testLinks()
    {
        $document = new ResourceCollectionDocument();

        $this->assertInstanceOf(LinksAwareInterface::class, $document);

        $link = $this->createMock(LinkObject::class);
        $document->setLink('test', $link);

        $this->assertFalse($document->hasLink('qwerty'));
        $this->assertTrue($document->hasLink('test'));
        $this->assertSame($link, $document->getLink('test'));
        $this->assertSame(['test' => $link], $document->getLinks());
    }
}