<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\ErrorsAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class SingleIdentifierDocumentTest extends TestCase
{
    public function testIdentifier()
    {
        $identifier = $this->createMock(ResourceIdentifierObject::class);
        $document   = new SingleIdentifierDocument($identifier);

        $this->assertSame($identifier, $document->getIdentifier());
    }

    public function testMetadata()
    {
        $identifier = $this->createMock(ResourceIdentifierObject::class);
        $document   = new SingleIdentifierDocument($identifier, ['test' => 42]);

        $this->assertInstanceOf(MetadataAwareInterface::class, $document);

        $this->assertFalse($document->hasMetadataAttribute('qwerty'));
        $this->assertTrue($document->hasMetadataAttribute('test'));
        $this->assertSame(42, $document->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $document->getMetadata());
    }

    public function testLinks()
    {
        $identifier = $this->createMock(ResourceIdentifierObject::class);
        $document   = new SingleIdentifierDocument($identifier);

        $this->assertInstanceOf(LinksAwareInterface::class, $document);

        $link = $this->createMock(LinkObject::class);
        $document->setLink('test', $link);

        $this->assertFalse($document->hasLink('qwerty'));
        $this->assertTrue($document->hasLink('test'));
        $this->assertSame($link, $document->getLink('test'));
        $this->assertSame(['test' => $link], $document->getLinks());
    }

    public function testToArrayIdentifier()
    {
        $identifier = $this->createMock(ResourceIdentifierObject::class);

        $identifier->expects($this->once())
            ->method('toArray')
            ->willReturn(['test' => 'qwerty']);

        $document = new SingleIdentifierDocument($identifier);

        $this->assertSame(
            [
                'data' => ['test' => 'qwerty']
            ],
            $document->toArray()
        );
    }

    public function testToArrayMetadata()
    {
        $identifier = $this->createMock(ResourceIdentifierObject::class);

        $document = new SingleIdentifierDocument($identifier);
        $document->setMetadataAttribute('test', 'qwerty');

        $this->assertSame(
            [
                'meta' => ['test' => 'qwerty'],
                'data' => []
            ],
            $document->toArray()
        );
    }

    public function testToArrayLinks()
    {
        $identifier = $this->createMock(ResourceIdentifierObject::class);
        $document   = new SingleIdentifierDocument($identifier);

        $link = $this->createMock(LinkObject::class);

        $link->method('getReference')
            ->willReturn('http://qwerty.com');

        $document->setLink('test', $link);

        $this->assertSame(
            [
                'links' => ['test' => 'http://qwerty.com'],
                'data'  => []
            ],
            $document->toArray()
        );
    }

    public function testErrors()
    {
        $resource = $this->createMock(ResourceIdentifierObject::class);
        $document = new SingleIdentifierDocument($resource);
        $error    = $this->createMock(ErrorObject::class);

        $this->assertInstanceOf(ErrorsAwareInterface::class, $document);
        $this->assertFalse($document->hasErrors());

        $document->addError($error);

        $this->assertTrue($document->hasErrors());
        $this->assertSame([$error], $document->getErrors());
    }

    public function testToArrayErrors()
    {
        $resource = $this->createMock(ResourceIdentifierObject::class);
        $document = new SingleIdentifierDocument($resource);

        $error = $this->createMock(ErrorObject::class);

        $error->method('toArray')
            ->willReturn(['test' => '123']);

        $document->addError($error);

        $this->assertSame(
            [['test' => '123']],
            $document->toArray()['errors']
        );
    }

    public function testJsonApi()
    {
        $resource = $this->createMock(ResourceIdentifierObject::class);
        $document = new SingleIdentifierDocument($resource);
        $jsonApi  = new JsonApiObject();

        $document->setJsonApi($jsonApi);

        $this->assertSame($jsonApi, $document->getJsonApi());
    }

    public function testJsonApiToArray()
    {
        $resource = $this->createMock(ResourceIdentifierObject::class);

        $resource->method('toArray')
            ->willReturn([]);

        $document = new SingleIdentifierDocument($resource);
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
}