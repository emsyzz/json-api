<?php

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\AbstractDocument;
use Mikemirten\Component\JsonApi\Document\JsonApiObject;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;
use PHPUnit\Framework\TestCase;

/**
 * @group hydrator
 */
class JsonApiExtensionTest extends TestCase
{
    public function testSupports()
    {
        $extension = new JsonApiExtension();

        $this->assertSame(['jsonapi'], $extension->supports());
    }

    public function testHydrate()
    {
        $document = $this->createMock(AbstractDocument::class);

        $document->expects($this->once())
            ->method('setJsonApi')
            ->with($this->isInstanceOf(JsonApiObject::class))
            ->willReturnCallback(function(JsonApiObject $jsonApi) {
                $this->assertSame('1.2.3', $jsonApi->getVersion());
            });

        $hydrator  = $this->createMock(DocumentHydrator::class);
        $extension = new JsonApiExtension();

        $extension->hydrate($document, (object) ['version' => '1.2.3'], $hydrator);
    }
}