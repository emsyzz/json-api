<?php

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\AbstractRelationship;
use Mikemirten\Component\JsonApi\Document\Behaviour\RelationshipsAwareInterface;
use Mikemirten\Component\JsonApi\Document\IdentifierCollectionRelationship;
use Mikemirten\Component\JsonApi\Document\NoDataRelationship;
use Mikemirten\Component\JsonApi\Document\SingleIdentifierRelationship;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;
use PHPUnit\Framework\TestCase;

/**
 * @group hydrator
 */
class RelationshipsExtensionTest extends TestCase
{
    public function testSupports()
    {
        $extension = new RelationshipExtension();

        $this->assertSame(['relationships'], $extension->supports());
    }

    public function testNoDataRelationship()
    {
        $object = $this->createMock(RelationshipsAwareInterface::class);

        $object->expects($this->once())
            ->method('setRelationship')
            ->with(
                'test',
                $this->isInstanceOf(NoDataRelationship::class)
            );

        $hydrator  = $this->createMock(DocumentHydrator::class);
        $extension = new RelationshipExtension();

        $extension->hydrate($object, json_decode('{"test": {}}'), $hydrator);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Exception\InvalidDocumentException
     */
    public function testMissingResourceId()
    {
        $object = $this->createMock(RelationshipsAwareInterface::class);

        $object->expects($this->never())
            ->method('setRelationship');

        $hydrator  = $this->createMock(DocumentHydrator::class);
        $extension = new RelationshipExtension();

        $extension->hydrate($object, json_decode('{"test": {"data": {"type": "Test"}}}'), $hydrator);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Exception\InvalidDocumentException
     */
    public function testMissingResourceType()
    {
        $object = $this->createMock(RelationshipsAwareInterface::class);

        $object->expects($this->never())
            ->method('setRelationship');

        $hydrator  = $this->createMock(DocumentHydrator::class);
        $extension = new RelationshipExtension();

        $extension->hydrate($object, json_decode('{"test": {"data": {"id": "1"}}}'), $hydrator);
    }

    public function testSingleResourceDocument()
    {
        $object = $this->createMock(RelationshipsAwareInterface::class);

        $object->expects($this->once())
            ->method('setRelationship')
            ->with(
                'test',
                $this->isInstanceOf(SingleIdentifierRelationship::class)
            )
            ->willReturnCallback(function(string $name, SingleIdentifierRelationship $relationship) {
                $identifier = $relationship->getIdentifier();

                $this->assertSame('1', $identifier->getId());
                $this->assertSame('Test', $identifier->getType());
            });

        $hydrator  = $this->createMock(DocumentHydrator::class);
        $extension = new RelationshipExtension();

        $extension->hydrate($object, json_decode('{"test": {"data": {"id": "1", "type": "Test"}}}'), $hydrator);
    }

    public function testEmptyResourceCollectionDocument()
    {
        $object = $this->createMock(RelationshipsAwareInterface::class);

        $object->expects($this->once())
            ->method('setRelationship')
            ->with(
                'test',
                $this->isInstanceOf(IdentifierCollectionRelationship::class)
            );

        $hydrator  = $this->createMock(DocumentHydrator::class);
        $extension = new RelationshipExtension();

        $extension->hydrate($object, json_decode('{"test": {"data": []}}'), $hydrator);
    }

    public function testResourceCollectionDocument()
    {
        $object = $this->createMock(RelationshipsAwareInterface::class);

        $object->expects($this->once())
            ->method('setRelationship')
            ->with(
                'test',
                $this->isInstanceOf(IdentifierCollectionRelationship::class)
            )
            ->willReturnCallback(function(string $name, IdentifierCollectionRelationship $relationship) {
                $identifier = $relationship->getIdentifiers()[0];

                $this->assertSame('1', $identifier->getId());
                $this->assertSame('Test', $identifier->getType());
            });

        $hydrator  = $this->createMock(DocumentHydrator::class);
        $extension = new RelationshipExtension();

        $extension->hydrate($object, json_decode('{"test": {"data": [{"id": "1", "type": "Test"}]}}'), $hydrator);
    }
}