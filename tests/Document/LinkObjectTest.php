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
}