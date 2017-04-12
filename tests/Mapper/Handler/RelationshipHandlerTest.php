<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Document\IdentifierCollectionRelationship;
use Mikemirten\Component\JsonApi\Document\NoDataRelationship;
use Mikemirten\Component\JsonApi\Document\ResourceIdentifierObject;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Document\SingleIdentifierRelationship;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\Relationship;
use Mikemirten\Component\JsonApi\Mapper\Handler\LinkHandler\LinkHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;
use Mikemirten\Component\JsonApi\Mapper\ObjectMapper;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
class RelationshipHandlerTest extends TestCase
{
    public function testToResource()
    {
        $related = new class {};
        $object  = new class($related)
        {
            protected $related;

            public function __construct($related)
            {
                $this->related = $related;
            }

            public function getRelationship()
            {
                return $this->related;
            }
        };

        $identifier = $this->createMock(ResourceIdentifierObject::class);
        $resource   = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setRelationship')
            ->with(
                'qwerty',
                $this->isInstanceOf(SingleIdentifierRelationship::class)
            )
            ->willReturnCallback(
                function(string $name, SingleIdentifierRelationship $relationship) use($identifier)
                {
                    $result = $relationship->getIdentifier();

                    $this->assertSame($identifier, $result);
                }
            );

        $linkHandler = $this->createMock(LinkHandlerInterface::class);

        $handler = new RelationshipHandler($linkHandler);

        $context = $this->createContext(
            $this->createDefinition([
                $this->createRelationshipDefinition('qwerty', 'getRelationship', false, true)
            ]),
            $this->createMapper($related, $identifier)
        );

        $handler->toResource($object, $resource, $context);
    }

    public function testToResourceNoDataIncluded()
    {
        $object   = new class {};
        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setRelationship')
            ->with(
                'qwerty',
                $this->isInstanceOf(NoDataRelationship::class)
            );

        $linkHandler = $this->createMock(LinkHandlerInterface::class);
        $handler     = new RelationshipHandler($linkHandler);

        $context = $this->createContext(
            $this->createDefinition([
                $this->createRelationshipDefinition('qwerty')
            ])
        );

        $handler->toResource($object, $resource, $context);
    }

    public function testToResourceNullableRelation()
    {
        $object   = new class {};
        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setRelationship')
            ->with(
                'qwerty',
                $this->isInstanceOf(NoDataRelationship::class)
            );

        $linkHandler = $this->createMock(LinkHandlerInterface::class);
        $handler     = new RelationshipHandler($linkHandler);

        $context = $this->createContext(
            $this->createDefinition([
                $this->createRelationshipDefinition('qwerty')
            ])
        );

        $handler->toResource($object, $resource, $context);
    }

    public function testCollectionToResource()
    {
        $related = new class {};
        $object  = new class($related)
        {
            protected $related;

            public function __construct($related)
            {
                $this->related = $related;
            }

            public function getRelationship()
            {
                return new \ArrayIterator([$this->related]);
            }
        };

        $identifier = $this->createMock(ResourceIdentifierObject::class);
        $resource   = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setRelationship')
            ->with(
                'qwerty',
                $this->isInstanceOf(IdentifierCollectionRelationship::class)
            )
            ->willReturnCallback(
                function(string $name, IdentifierCollectionRelationship $relationship) use($identifier)
                {
                    $result = $relationship->getIdentifiers();

                    $this->assertCount(1, $result);
                    $this->assertArrayHasKey(0, $result);
                    $this->assertSame($result[0], $identifier);
                }
            );

        $linkHandler = $this->createMock(LinkHandlerInterface::class);
        $handler     = new RelationshipHandler($linkHandler);

        $context = $this->createContext(
            $this->createDefinition([
                $this->createRelationshipDefinition('qwerty', 'getRelationship', true, true)
            ]),
            $this->createMapper($related, $identifier)
        );

        $handler->toResource($object, $resource, $context);
    }

    public function testCollectionToResourceWithLimit()
    {
        $related = new class {};
        $object  = new class($related)
        {
            protected $related;

            public function __construct($related)
            {
                $this->related = $related;
            }

            public function getRelationship()
            {
                return new \ArrayIterator([$this->related, $this->related]);
            }
        };

        $identifier = $this->createMock(ResourceIdentifierObject::class);
        $resource   = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setRelationship')
            ->with(
                'qwerty',
                $this->isInstanceOf(IdentifierCollectionRelationship::class)
            )
            ->willReturnCallback(
                function(string $name, IdentifierCollectionRelationship $relationship) use($identifier)
                {
                    $result = $relationship->getIdentifiers();

                    $this->assertCount(1, $result);
                    $this->assertArrayHasKey(0, $result);
                    $this->assertSame($result[0], $identifier);
                }
            );

        $linkHandler = $this->createMock(LinkHandlerInterface::class);
        $handler     = new RelationshipHandler($linkHandler);

        $context = $this->createContext(
            $this->createDefinition([
                $this->createRelationshipDefinition('qwerty', 'getRelationship', true, true, 1)
            ]),
            $this->createMapper($related, $identifier)
        );

        $handler->toResource($object, $resource, $context);
    }

    /**
     * Create mock of mapping context including definition
     *
     * @param  Definition   $definition
     * @param  ObjectMapper $mapper
     * @return MappingContext
     */
    protected function createContext(Definition $definition, ObjectMapper $mapper = null): MappingContext
    {
        $context = $this->createMock(MappingContext::class);

        $context->expects($this->once())
            ->method('getDefinition')
            ->willReturn($definition);

        if ($mapper !== null) {
            $context->expects($this->once())
                ->method('getMapper')
                ->willReturn($mapper);
        }

        return $context;
    }

    /**
     * Create mock of object mapper
     *
     * @param  mixed
     * @param  ResourceIdentifierObject $identifier
     * @return ObjectMapper
     */
    protected function createMapper($object, ResourceIdentifierObject $identifier): ObjectMapper
    {
        $mapper = $this->createMock(ObjectMapper::class);

        $mapper->expects($this->once())
            ->method('toResourceIdentifier')
            ->with($object)
            ->willReturn($identifier);

        return $mapper;
    }

    /**
     * Create mock of mapping definition
     *
     * @param  array $relationships
     * @return Definition
     */
    protected function createDefinition(array $relationships = null): Definition
    {
        $definition = $this->createMock(Definition::class);

        if ($relationships !== null) {
            $definition->expects($this->once())
                ->method('getRelationships')
                ->willReturn($relationships);
        }

        return $definition;
    }

    /**
     * Create mock of mapping definition of a relationship
     *
     * @param string $name
     * @param string $getter
     * @param bool   $toMany
     * @param bool   $dataIncluded
     * @param int    $limit
     *
     * @return Relationship
     */
    protected function createRelationshipDefinition(string $name, string $getter = 'get', bool $toMany = false, bool $dataIncluded = false, int $limit = 0): Relationship
    {
        $relationship = $this->createMock(Relationship::class);

        $relationship->method('getName')
            ->willReturn($name);

        $relationship->method('getGetter')
            ->willReturn($getter);

        $relationship->method('isCollection')
            ->willReturn($toMany);

        $relationship->method('isDataIncluded')
            ->willReturn($dataIncluded);

        $relationship->method('getDataLimit')
            ->willReturn($limit);

        return $relationship;
    }
}