<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class JsonApiObjectTest extends TestCase
{
    public function testVersion()
    {
        $jsonApi = new JsonApiObject('2.3.4');

        $this->assertSame('2.3.4', $jsonApi->getVersion());
    }

    public function testSetVersion()
    {
        $jsonApi = new JsonApiObject();
        $jsonApi->setVersion('2.3.5');

        $this->assertSame('2.3.5', $jsonApi->getVersion());
    }

    public function testMetadata()
    {
        $document = new JsonApiObject('1.0', ['test' => 42]);

        $this->assertInstanceOf(MetadataAwareInterface::class, $document);

        $this->assertFalse($document->hasMetadataAttribute('qwerty'));
        $this->assertTrue($document->hasMetadataAttribute('test'));
        $this->assertSame(42, $document->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $document->getMetadata());
    }
}