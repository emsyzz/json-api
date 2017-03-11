<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
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

        $this->assertInstanceOf(MetadataAwareInterface::class, $document);

        $this->assertFalse($document->hasMetadataAttribute('qwerty'));
        $this->assertTrue($document->hasMetadataAttribute('test'));
        $this->assertSame(42, $document->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $document->getMetadata());
    }

    public function testLinks()
    {
        $resource = $this->createMock(ResourceObject::class);
        $document = new SingleResourceDocument($resource);

        $this->assertInstanceOf(LinksAwareInterface::class, $document);

        $link = $this->createMock(LinkObject::class);
        $document->setLink('test', $link);

        $this->assertFalse($document->hasLink('qwerty'));
        $this->assertTrue($document->hasLink('test'));
        $this->assertSame($link, $document->getLink('test'));
        $this->assertSame(['test' => $link], $document->getLinks());
    }

    public function testToArray()
    {
        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('toArray')
            ->willReturn(['test' => 'qwerty']);

        $document = new SingleResourceDocument($resource);

        $document->setLink(
            'test_link',
            $this->createLink(
                'http://test_link.com',
                ['test' => 123]
            )
        );

        $this->assertSame(
            [
                'links' => [
                    'test_link' => [
                        'href' => 'http://test_link.com',
                        'meta' => ['test' => 123]
                    ]
                ],
                'data' => [
                    'test' => 'qwerty'
                ]
            ],
            $document->toArray()
        );
    }

    public function createLink(string $reference, array $metadata = []): LinkObject
    {
        $link = $this->createMock(LinkObject::class);

        $link->method('hasMetadata')
            ->willReturn(! empty($metadata));

        $link->method('getMetadata')
            ->willReturn($metadata);

        $link->method('getReference')
            ->willReturn($reference);

        return $link;
    }
}