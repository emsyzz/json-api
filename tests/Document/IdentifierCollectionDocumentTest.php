<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\ErrorsAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class IdentifierCollectionDocumentTest extends TestCase
{
    public function testIdentifiers()
    {
        $document = new IdentifierCollectionDocument();

        $this->assertEmpty($document->getIdentifiers());

        $identifier = $this->createMock(ResourceIdentifierObject::class);
        $document->addIdentifier($identifier);

        $this->assertSame($identifier, $document->getFirstIdentifier());
        $this->assertSame([$identifier], $document->getIdentifiers());
    }

    public function testIterator()
    {
        $identifier = $this->createMock(ResourceIdentifierObject::class);

        $document = new IdentifierCollectionDocument();
        $document->addIdentifier($identifier);

        $this->assertSame([$identifier], iterator_to_array($document));
    }

    public function testMetadata()
    {
        $document = new IdentifierCollectionDocument(['test' => 42]);

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
        $document = new IdentifierCollectionDocument();
        $document->setMetadataAttribute('test', 42);

        $this->assertTrue($document->hasMetadataAttribute('test'));

        $document->removeMetadataAttribute('test');

        $this->assertFalse($document->hasMetadataAttribute('test'));
    }

    public function testErrors()
    {
        $document = new IdentifierCollectionDocument();
        $error    = $this->createMock(ErrorObject::class);

        $this->assertInstanceOf(ErrorsAwareInterface::class, $document);
        $this->assertFalse($document->hasErrors());

        $document->addError($error);

        $this->assertTrue($document->hasErrors());
        $this->assertSame([$error], $document->getErrors());
    }

    public function testToArrayErrors()
    {
        $document = new IdentifierCollectionDocument();

        $error = $this->createMock(ErrorObject::class);

        $error->method('toArray')
            ->willReturn(['test' => '123']);

        $document->addError($error);

        $this->assertSame(
            [['test' => '123']],
            $document->toArray()['errors']
        );
    }

    public function testLinks()
    {
        $relationship = new IdentifierCollectionDocument();

        $this->assertInstanceOf(LinksAwareInterface::class, $relationship);

        $link = $this->createMock(LinkObject::class);
        $relationship->setLink('test', $link);

        $this->assertFalse($relationship->hasLink('qwerty'));
        $this->assertTrue($relationship->hasLink('test'));
        $this->assertSame($link, $relationship->getLink('test'));
        $this->assertSame(['test' => $link], $relationship->getLinks());
    }

    public function testToArrayResources()
    {
        $resource = $this->createMock(ResourceIdentifierObject::class);

        $resource->expects($this->once())
            ->method('toArray')
            ->willReturn(['test' => 'qwerty']);

        $relationship = new IdentifierCollectionDocument();
        $relationship->addIdentifier($resource);

        $this->assertSame(
            [
                'data' => [
                    ['test' => 'qwerty']
                ]
            ],
            $relationship->toArray()
        );
    }

    public function testToArrayLinks()
    {
        $relationship = new IdentifierCollectionDocument();
        $relationship->setLink('test_link', $this->createLink(
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
                ],
                'data' => []
            ],
            $relationship->toArray());
    }

    public function testToArrayMetadata()
    {
        $relationship = new IdentifierCollectionDocument();
        $relationship->setMetadataAttribute('test', 'qwerty');

        $this->assertSame(
            [
                'meta' => ['test' => 'qwerty'],
                'data' => []
            ],
            $relationship->toArray()
        );
    }

    public function testJsonApi()
    {
        $document = new IdentifierCollectionDocument();
        $jsonApi  = new JsonApiObject();

        $document->setJsonApi($jsonApi);

        $this->assertSame($jsonApi, $document->getJsonApi());
    }

    public function testJsonApiToArray()
    {
        $document = new IdentifierCollectionDocument();
        $jsonApi  = new JsonApiObject();

        $document->setJsonApi($jsonApi);

        $this->assertSame(
            [
                'jsonapi' => ['version' => '1.0'],
                'data'    => []
            ],
            $document->toArray()
        );
    }

    public function testIncluded()
    {
        $document = new IdentifierCollectionDocument();
        $resource = $this->createMock(ResourceObject::class);

        $this->assertFalse($document->hasIncludedResources());

        $document->addIncludedResource($resource);

        $this->assertTrue($document->hasIncludedResources());
        $this->assertSame([$resource], $document->getIncludedResources());
    }

    public function testIncludedToArray()
    {
        $document = new IdentifierCollectionDocument();
        $resource = $this->createMock(ResourceObject::class);

        $resource->method('toArray')
            ->willReturn(['test' => 'qwerty']);

        $document->addIncludedResource($resource);

        $this->assertSame(
            [
                'included' => [
                    ['test' => 'qwerty']
                ],
                'data' => []
            ],
            $document->toArray()
        );
    }

    public function testToString()
    {
        $document = new IdentifierCollectionDocument();

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
        $document = new IdentifierCollectionDocument();

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
        $document = new IdentifierCollectionDocument();

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