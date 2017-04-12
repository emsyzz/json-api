<?php

namespace Mikemirten\Component\JsonApi\Mapper;

use Mikemirten\Component\JsonApi\Document\ResourceIdentifierObject;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\DefinitionProviderInterface;
use Mikemirten\Component\JsonApi\Mapper\Handler\HandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\Handler\IdentifierHandler\IdentifierHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\Handler\TypeHandler\TypeHandlerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper
 */
class ObjectMapperTest extends TestCase
{
    public function testToResource()
    {
        $object = new \stdClass();

        $identifierHandler  = $this->createMock(IdentifierHandlerInterface::class);
        $typeHandler        = $this->createMock(TypeHandlerInterface::class);
        $extraHandler       = $this->createMock(HandlerInterface::class);
        $definitionProvider = $this->createMock(DefinitionProviderInterface::class);

        $definitionProvider->expects($this->once())
            ->method('getDefinition')
            ->with('stdClass')
            ->willReturn($this->createMock(Definition::class));

        $identifierHandler->expects($this->once())
            ->method('getIdentifier')
            ->with(
                $object,
                $this->isInstanceOf(MappingContext::class)
            )
            ->willReturn('123');

        $typeHandler->expects($this->once())
            ->method('getType')
            ->with(
                $object,
                $this->isInstanceOf(MappingContext::class)
            )
            ->willReturn('stdClass');

        $extraHandler->expects($this->once())
            ->method('toResource')
            ->with(
                $object,
                $this->isInstanceOf(ResourceObject::class),
                $this->isInstanceOf(MappingContext::class)
            );

        $mapper = new ObjectMapper($definitionProvider, $identifierHandler, $typeHandler);
        $mapper->addHandler($extraHandler);

        $resource = $mapper->toResource($object);

        $this->assertInstanceOf(ResourceObject::class, $resource);
        $this->assertSame('123', $resource->getId());
        $this->assertSame('stdClass', $resource->getType());
    }

    public function testToResourceIdentifier()
    {
        $object = new \stdClass();

        $identifierHandler  = $this->createMock(IdentifierHandlerInterface::class);
        $typeHandler        = $this->createMock(TypeHandlerInterface::class);
        $definitionProvider = $this->createMock(DefinitionProviderInterface::class);

        $definitionProvider->expects($this->once())
            ->method('getDefinition')
            ->with('stdClass')
            ->willReturn($this->createMock(Definition::class));

        $identifierHandler->expects($this->once())
            ->method('getIdentifier')
            ->with(
                $object,
                $this->isInstanceOf(MappingContext::class)
            )
            ->willReturn('123');

        $typeHandler->expects($this->once())
            ->method('getType')
            ->with(
                $object,
                $this->isInstanceOf(MappingContext::class)
            )
            ->willReturn('stdClass');

        $mapper = new ObjectMapper($definitionProvider, $identifierHandler, $typeHandler);

        $resource = $mapper->toResourceIdentifier($object);

        $this->assertInstanceOf(ResourceIdentifierObject::class, $resource);
        $this->assertSame('123', $resource->getId());
        $this->assertSame('stdClass', $resource->getType());
    }

    public function testFromResource()
    {
        $object   = new \stdClass();
        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('getId')
            ->willReturn('123');

        $identifierHandler  = $this->createMock(IdentifierHandlerInterface::class);
        $typeHandler        = $this->createMock(TypeHandlerInterface::class);
        $extraHandler       = $this->createMock(HandlerInterface::class);
        $definitionProvider = $this->createMock(DefinitionProviderInterface::class);

        $definitionProvider->expects($this->once())
            ->method('getDefinition')
            ->with('stdClass')
            ->willReturn($this->createMock(Definition::class));

        $identifierHandler->expects($this->once())
            ->method('setIdentifier')
            ->with(
                $object,
                '123',
                $this->isInstanceOf(MappingContext::class)
            );

        $extraHandler->expects($this->once())
            ->method('fromResource')
            ->with(
                $object,
                $resource,
                $this->isInstanceOf(MappingContext::class)
            );

        $mapper = new ObjectMapper($definitionProvider, $identifierHandler, $typeHandler);
        $mapper->addHandler($extraHandler);

        $mapper->fromResource($object, $resource);
    }
}