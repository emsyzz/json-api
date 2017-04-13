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

    public function testToString()
    {
        $object = new JsonApiObject('1.0');

        $this->assertRegExp('~JsonAPI\-object~', (string) $object);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeNotFoundException
     *
     * @expectedExceptionMessageRegExp ~JsonAPI\-object~
     * @expectedExceptionMessageRegExp ~test_attribute~
     */
    public function testMetadataNotFound()
    {
        $object = new JsonApiObject('1.0');

        $object->getMetadataAttribute('test_attribute');
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeOverrideException
     *
     * @expectedExceptionMessageRegExp ~JsonAPI\-object~
     * @expectedExceptionMessageRegExp ~test_attribute~
     */
    public function testMetadataOverride()
    {
        $object = new JsonApiObject('1.0');

        $object->setMetadataAttribute('test_attribute', 1);
        $object->setMetadataAttribute('test_attribute', 2);
    }
}