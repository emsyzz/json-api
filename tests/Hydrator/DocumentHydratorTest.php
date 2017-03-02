<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Hydrator;

use Mikemirten\Component\JsonApi\Document\AbstractDocument;
use Mikemirten\Component\JsonApi\Document\NoDataDocument;
use Mikemirten\Component\JsonApi\Document\ResourceCollectionDocument;
use Mikemirten\Component\JsonApi\Document\SingleResourceDocument;
use Mikemirten\Component\JsonApi\Hydrator\Extension\ExtensionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group hydrator
 */
class DocumentHydratorTest extends TestCase
{
    public function testNoDataDocument()
    {
        $hydrator = new DocumentHydrator();
        $document = $hydrator->hydrate(json_decode('{}'));

        $this->assertInstanceOf(NoDataDocument::class, $document);
    }

    public function testSingleResourceDocument()
    {
        $hydrator = new DocumentHydrator();
        $document = $hydrator->hydrate(json_decode('{"data": {"id": "1", "type": "Test", "attributes": {"test": 123}}}'));

        $this->assertInstanceOf(SingleResourceDocument::class, $document);

        $resource = $document->getResource();

        $this->assertSame('1', $resource->getId());
        $this->assertSame('Test', $resource->getType());
        $this->assertSame(['test' => 123], $resource->getAttributes());
    }

    public function testEmptyResourceCollectionDocument()
    {
        $hydrator = new DocumentHydrator();
        $document = $hydrator->hydrate(json_decode('{"data": []}'));

        $this->assertInstanceOf(ResourceCollectionDocument::class, $document);

    }

    public function testResourceCollectionDocument()
    {
        $hydrator = new DocumentHydrator();
        $document = $hydrator->hydrate(json_decode('{"data": [{"id": "1", "type": "Test", "attributes": {"test": 123}}]}'));

        $this->assertInstanceOf(ResourceCollectionDocument::class, $document);

        $resource = $document->getResources()[0];

        $this->assertSame('1', $resource->getId());
        $this->assertSame('Test', $resource->getType());
        $this->assertSame(['test' => 123], $resource->getAttributes());
    }

    public function testExtension()
    {
        $hydrator  = new DocumentHydrator();
        $extension = $this->createMock(ExtensionInterface::class);

        $extension->expects($this->once())
            ->method('supports')
            ->willReturn(['test']);

        $extension->expects($this->once())
            ->method('hydrate')
            ->with(
                $this->isInstanceOf(AbstractDocument::class),
                $this->isInstanceOf(\stdClass::class),
                $hydrator
            );


        $hydrator->registerExtension($extension);

        $hydrator->hydrate(json_decode('{"test": {}}'));
    }
}