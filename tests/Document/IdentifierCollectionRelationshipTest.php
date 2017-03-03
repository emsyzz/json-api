<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class IdentifierCollectionRelationshipTest extends TestCase
{
    public function testIdentifiers()
    {
        $relationship = new IdentifierCollectionRelationship();

        $this->assertEmpty($relationship->getIdentifiers());

        $identifier = $this->createMock(ResourceIdentifierObject::class);
        $relationship->addIdentifier($identifier);

        $this->assertSame($identifier, $relationship->getFirstIdentifier());
        $this->assertSame([$identifier], $relationship->getIdentifiers());
    }

    public function testIterator()
    {
        $identifier = $this->createMock(ResourceIdentifierObject::class);

        $relationship = new IdentifierCollectionRelationship();
        $relationship->addIdentifier($identifier);

        $this->assertSame([$identifier], iterator_to_array($relationship));
    }

    public function testMetadata()
    {
        $relationship = new IdentifierCollectionRelationship(['test' => 42]);

        $this->assertInstanceOf(MetadataAwareInterface::class, $relationship);

        $this->assertFalse($relationship->hasMetadataAttribute('qwerty'));
        $this->assertTrue($relationship->hasMetadataAttribute('test'));
        $this->assertSame(42, $relationship->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $relationship->getMetadata());
    }

    public function testLinks()
    {
        $relationship = new IdentifierCollectionRelationship();

        $this->assertInstanceOf(LinksAwareInterface::class, $relationship);

        $link = $this->createMock(LinkObject::class);
        $relationship->setLink('test', $link);

        $this->assertFalse($relationship->hasLink('qwerty'));
        $this->assertTrue($relationship->hasLink('test'));
        $this->assertSame($link, $relationship->getLink('test'));
        $this->assertSame(['test' => $link], $relationship->getLinks());
    }
}