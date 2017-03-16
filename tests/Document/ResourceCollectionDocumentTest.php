<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\ErrorsAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
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

    public function testToArrayResources()
    {
        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('toArray')
            ->willReturn(['test' => 'qwerty']);

        $document = new ResourceCollectionDocument();
        $document->addResource($resource);

        $this->assertSame(
            [
                'data' => [
                    ['test' => 'qwerty']
                ]
            ],
            $document->toArray()
        );
    }

    public function testToArrayLinks()
    {
        $document = new ResourceCollectionDocument();


        $document->setLink('test_link', $this->createLink(
            'http://test_link.com',
            ['test' => 123]
        ));

        $this->assertSame(
            [
                'links' => [
                    'test_link' => [
                        'href' => 'http://test_link.com',
                        'meta' => ['test' => 123]
                    ]
                ],
                'data' => []
            ],
            $document->toArray()
        );
    }

    public function testToArrayMetadata()
    {
        $relationship = new ResourceCollectionDocument();
        $relationship->setMetadataAttribute('test', 'qwerty');

        $this->assertSame(
            [
                'meta' => ['test' => 'qwerty'],
                'data' => []
            ],
            $relationship->toArray()
        );
    }

    public function testErrors()
    {
        $document = new ResourceCollectionDocument();
        $error    = $this->createMock(ErrorObject::class);

        $this->assertInstanceOf(ErrorsAwareInterface::class, $document);
        $this->assertFalse($document->hasErrors());

        $document->addError($error);

        $this->assertTrue($document->hasErrors());
        $this->assertSame([$error], $document->getErrors());
    }

    public function testToArrayErrors()
    {
        $document = new ResourceCollectionDocument();

        $error = $this->createMock(ErrorObject::class);

        $error->method('toArray')
            ->willReturn(['test' => '123']);

        $document->addError($error);

        $this->assertSame(
            [['test' => '123']],
            $document->toArray()['errors']
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