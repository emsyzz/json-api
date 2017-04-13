<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class ResourceIdentifierObjectTest extends TestCase
{
    public function testBasics()
    {
        $resource = new ResourceIdentifierObject('42', 'test');

        $this->assertSame('42', $resource->getId());
        $this->assertSame('test', $resource->getType());
    }

    public function testMetadata()
    {
        $resource = new ResourceIdentifierObject('42', 'test', [
            'test' => 42
        ]);

        $this->assertInstanceOf(MetadataAwareInterface::class, $resource);

        $this->assertFalse($resource->hasMetadataAttribute('qwerty'));
        $this->assertTrue($resource->hasMetadataAttribute('test'));
        $this->assertSame(42, $resource->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $resource->getMetadata());
    }

    public function testToArrayBasics()
    {
        $resource = new ResourceIdentifierObject('42', 'test');

        $this->assertSame(
            [
                'id'   => '42',
                'type' => 'test'
            ],
            $resource->toArray()
        );
    }

    public function testToArrayMetadata()
    {
        $resource = new ResourceIdentifierObject('42', 'test');
        $resource->setMetadataAttribute('test_attr', 'qwerty');

        $this->assertSame(
            ['test_attr' => 'qwerty'],
            $resource->toArray()['meta']
        );
    }

    public function testToString()
    {
        $resource = new ResourceIdentifierObject('42', 'test');

        $this->assertRegExp('~Identifier~', (string) $resource);
        $this->assertRegExp('~42~', (string) $resource);
        $this->assertRegExp('~test~', (string) $resource);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeNotFoundException
     *
     * @expectedExceptionMessageRegExp ~Identifier~
     * @expectedExceptionMessageRegExp ~test_attribute~
     */
    public function testMetadataNotFound()
    {
        $resource = new ResourceIdentifierObject('42', 'test');

        $resource->getMetadataAttribute('test_attribute');
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeOverrideException
     *
     * @expectedExceptionMessageRegExp ~Identifier~
     * @expectedExceptionMessageRegExp ~test_attribute~
     */
    public function testMetadataOverride()
    {
        $resource = new ResourceIdentifierObject('42', 'test');

        $resource->setMetadataAttribute('test_attribute', 1);
        $resource->setMetadataAttribute('test_attribute', 2);
    }
}