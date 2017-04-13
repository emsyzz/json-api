<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\ErrorsAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class NoDataDocumentTest extends TestCase
{
    public function testMetadata()
    {
        $document = new NoDataDocument(['test' => 42]);

        $this->assertInstanceOf(MetadataAwareInterface::class, $document);

        $this->assertFalse($document->hasMetadataAttribute('qwerty'));
        $this->assertTrue($document->hasMetadataAttribute('test'));
        $this->assertSame(42, $document->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $document->getMetadata());
    }

    /**
     * @depends testMetadata
     */
    public function testMetadataRemove()
    {
        $document = new NoDataDocument();
        $document->setMetadataAttribute('test', 42);

        $this->assertTrue($document->hasMetadataAttribute('test'));

        $document->removeMetadataAttribute('test');

        $this->assertFalse($document->hasMetadataAttribute('test'));
    }

    public function testLinks()
    {
        $document = new NoDataDocument();

        $this->assertInstanceOf(LinksAwareInterface::class, $document);

        $link = $this->createMock(LinkObject::class);
        $document->setLink('test', $link);

        $this->assertFalse($document->hasLink('qwerty'));
        $this->assertTrue($document->hasLink('test'));
        $this->assertSame($link, $document->getLink('test'));
        $this->assertSame(['test' => $link], $document->getLinks());
    }

    public function testErrors()
    {
        $document = new NoDataDocument();
        $error    = $this->createMock(ErrorObject::class);

        $this->assertInstanceOf(ErrorsAwareInterface::class, $document);
        $this->assertFalse($document->hasErrors());

        $document->addError($error);

        $this->assertTrue($document->hasErrors());
        $this->assertSame([$error], $document->getErrors());
    }

    public function testToArrayErrors()
    {
        $document = new NoDataDocument();

        $error = $this->createMock(ErrorObject::class);

        $error->method('toArray')
            ->willReturn(['test' => '123']);

        $document->addError($error);

        $this->assertSame(
            [['test' => '123']],
            $document->toArray()['errors']
        );
    }

    public function testToArrayLinks()
    {
        $document = new NoDataDocument();
        $document->setLink('test_link', $this->createLink(
            'http://test.com',
            ['link_meta' => 123]
        ));

        $this->assertSame(
            [
                'links' => [
                    'test_link' => [
                        'href' => 'http://test.com',
                        'meta' => [
                            'link_meta' => 123
                        ]
                    ]
                ]
            ],
            $document->toArray());
    }

    public function testToArrayMetadata()
    {
        $document = new NoDataDocument();
        $document->setMetadataAttribute('test_meta', 456);

        $this->assertSame(
            [
                'meta' => [
                    'test_meta' => 456
                ]
            ],
            $document->toArray()
        );
    }

    public function testJsonApi()
    {
        $document = new NoDataDocument();
        $jsonApi  = new JsonApiObject();

        $document->setJsonApi($jsonApi);

        $this->assertSame($jsonApi, $document->getJsonApi());
    }

    public function testJsonApiToArray()
    {
        $document = new NoDataDocument();
        $jsonApi  = new JsonApiObject();

        $document->setJsonApi($jsonApi);

        $this->assertSame(
            [
                'jsonapi' => ['version' => '1.0']
            ],
            $document->toArray()
        );
    }

    public function testIncluded()
    {
        $document = new NoDataDocument();
        $resource = $this->createMock(ResourceObject::class);

        $this->assertFalse($document->hasIncludedResources());

        $document->addIncludedResource($resource);

        $this->assertTrue($document->hasIncludedResources());
        $this->assertSame([$resource], $document->getIncludedResources());
    }

    public function testIncludedToArray()
    {
        $document = new NoDataDocument();
        $resource = $this->createMock(ResourceObject::class);

        $resource->method('toArray')
            ->willReturn(['test' => 'qwerty']);

        $document->addIncludedResource($resource);

        $this->assertSame(
            [
                'included' => [
                    ['test' => 'qwerty']
                ]
            ],
            $document->toArray()
        );
    }

    public function testToString()
    {
        $document = new NoDataDocument();

        $this->assertRegExp('~Document~', (string) $document);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeNotFoundException
     *
     * @expectedExceptionMessageRegExp ~Document~
     * @expectedExceptionMessageRegExp ~test_attribute~
     */
    public function testMetadataNotFound()
    {
        $document = new NoDataDocument();

        $document->getMetadataAttribute('test_attribute');
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeOverrideException
     *
     * @expectedExceptionMessageRegExp ~Document~
     * @expectedExceptionMessageRegExp ~test_attribute~
     */
    public function testMetadataOverride()
    {
        $document = new NoDataDocument();

        $document->setMetadataAttribute('test_attribute', 1);
        $document->setMetadataAttribute('test_attribute', 2);
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