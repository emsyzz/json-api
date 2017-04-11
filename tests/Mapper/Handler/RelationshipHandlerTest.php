<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Document\IdentifierCollectionRelationship;
use Mikemirten\Component\JsonApi\Document\NoDataRelationship;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Document\SingleIdentifierRelationship;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\Relationship;
use Mikemirten\Component\JsonApi\Mapper\Handler\LinkHandler\LinkHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
class RelationshipHandlerTest extends TestCase
{
    public function testToResource()
    {
        $object = new class
        {
            public function getRelationship()
            {
                return new class
                {
                    public function getId()
                    {
                        return '12345';
                    }
                };
            }
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setRelationship')
            ->with(
                'qwerty',
                $this->isInstanceOf(SingleIdentifierRelationship::class)
            )
            ->willReturnCallback(function(string $name, SingleIdentifierRelationship $relationship) {
                $identifier = $relationship->getIdentifier();

                $this->assertSame('12345', $identifier->getId());
                $this->assertSame('stdClass', $identifier->getType());
            });

        $linkHandler = $this->createMock(LinkHandlerInterface::class);

        $handler = new RelationshipHandler($linkHandler);
        $context = $this->createContext();

        $handler->toResource($object, $resource, $context);
    }

    public function testToResourceNullableRelation()
    {
        $object = new class
        {
            public function getRelationship() {}
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setRelationship')
            ->with(
                'qwerty',
                $this->isInstanceOf(NoDataRelationship::class)
            );

        $linkHandler = $this->createMock(LinkHandlerInterface::class);

        $handler = new RelationshipHandler($linkHandler);
        $context = $this->createContext();

        $handler->toResource($object, $resource, $context);
    }

    public function testCollectionToResource()
    {
        $object = new class
        {
            public function getRelationship()
            {
                return [new class
                {
                    public function getId()
                    {
                        return '12345';
                    }
                }];
            }
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setRelationship')
            ->with(
                'qwerty',
                $this->isInstanceOf(IdentifierCollectionRelationship::class)
            )
            ->willReturnCallback(function(string $name, IdentifierCollectionRelationship $relationship) {
                $identifier = $relationship->getIdentifiers()[0];

                $this->assertSame('12345', $identifier->getId());
                $this->assertSame('stdClass', $identifier->getType());
            });

        $linkHandler = $this->createMock(LinkHandlerInterface::class);

        $handler = new RelationshipHandler($linkHandler);
        $context = $this->createContext(true);

        $handler->toResource($object, $resource, $context);
    }

    protected function createContext($toMany = false): MappingContext
    {
        $relationship = $this->createMock(Relationship::class);

        $relationship->method('getName')
            ->willReturn('qwerty');

        $relationship->method('getGetter')
            ->willReturn('getRelationship');

        $relationship->method('hasIdentifierGetter')
            ->willReturn('getId');

        $relationship->method('getIdentifierGetter')
            ->willReturn('getId');

        $relationship->method('hasResourceType')
            ->willReturn(true);

        $relationship->method('getResourceType')
            ->willReturn('stdClass');

        $relationship->method('isCollection')
            ->willReturn($toMany);

        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('getRelationships')
            ->willReturn([$relationship]);

        $context = $this->createMock(MappingContext::class);

        $context->expects($this->once())
            ->method('getDefinition')
            ->willReturn($definition);

        return $context;
    }
}