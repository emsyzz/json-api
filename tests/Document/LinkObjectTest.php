<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class LinkObjectTest extends TestCase
{
    public function testReference()
    {
        $link = new LinkObject('http://test.com');

        $this->assertSame('http://test.com', $link->getReference());
    }

    public function testMetadata()
    {
        $link = new LinkObject('http://test.com', [
            'test' => 42
        ]);

        $this->assertInstanceOf(MetadataAwareInterface::class, $link);

        $this->assertFalse($link->hasMetadataAttribute('qwerty'));
        $this->assertTrue($link->hasMetadataAttribute('test'));
        $this->assertSame(42, $link->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $link->getMetadata());
    }

    public function testToString()
    {
        $link = new LinkObject('http://test.com');

        $this->assertRegExp('~Link~', (string) $link);
        $this->assertRegExp('~http\://test\.com~', (string) $link);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeNotFoundException
     *
     * @expectedExceptionMessageRegExp ~Link~
     * @expectedExceptionMessageRegExp ~test_attribute~
     */
    public function testMetadataNotFound()
    {
        $link = new LinkObject('http://test.com');

        $link->getMetadataAttribute('test_attribute');
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Document\Exception\MetadataAttributeOverrideException
     *
     * @expectedExceptionMessageRegExp ~Link~
     * @expectedExceptionMessageRegExp ~test_attribute~
     */
    public function testMetadataOverride()
    {
        $link = new LinkObject('http://test.com');

        $link->setMetadataAttribute('test_attribute', 1);
        $link->setMetadataAttribute('test_attribute', 2);
    }
}