<?php

namespace Mikemirten\Component\JsonApi\Document;

use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class NoDataDocumentTest extends TestCase
{
    public function testMetadata()
    {
        $document = new NoDataDocument(['test' => 42]);

        $this->assertFalse($document->hasMetadataAttribute('qwerty'));
        $this->assertTrue($document->hasMetadataAttribute('test'));
        $this->assertSame(42, $document->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $document->getMetadata());
    }

    public function testLinks()
    {
        $link = $this->createMock(LinkObject::class);

        $document = new NoDataDocument();
        $document->setLink('test', $link);

        $this->assertFalse($document->hasLink('qwerty'));
        $this->assertTrue($document->hasLink('test'));
        $this->assertSame($link, $document->getLink('test'));
        $this->assertSame(['test' => $link], $document->getLinks());
    }
}