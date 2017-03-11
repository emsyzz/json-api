<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class NoDataRelationshipTest extends TestCase
{
    public function testMetadata()
    {
        $relationship = new NoDataRelationship(['test' => 42]);

        $this->assertInstanceOf(MetadataAwareInterface::class, $relationship);

        $this->assertFalse($relationship->hasMetadataAttribute('qwerty'));
        $this->assertTrue($relationship->hasMetadataAttribute('test'));
        $this->assertSame(42, $relationship->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $relationship->getMetadata());
    }

    public function testLinks()
    {
        $relationship = new NoDataRelationship();

        $this->assertInstanceOf(LinksAwareInterface::class, $relationship);

        $link = $this->createMock(LinkObject::class);
        $relationship->setLink('test', $link);

        $this->assertFalse($relationship->hasLink('qwerty'));
        $this->assertTrue($relationship->hasLink('test'));
        $this->assertSame($link, $relationship->getLink('test'));
        $this->assertSame(['test' => $link], $relationship->getLinks());
    }

    public function testToArrayMetadata()
    {
        $relationship = new NoDataRelationship();
        $relationship->setMetadataAttribute('test', 'qwerty');

        $this->assertSame(
            [
                'meta' => ['test' => 'qwerty']
            ],
            $relationship->toArray()
        );
    }

    public function testToArrayLinks()
    {
        $document = new NoDataRelationship();
        $document->setLink('test_link', $this->createLink(
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
                ]
            ],
            $document->toArray());
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