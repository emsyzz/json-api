<?php

namespace Mikemirten\Component\JsonApi\Mapper;

use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\DeserializationContext;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\IdentifierHandler\IdentifierHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\TypeHandler\TypeHandlerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\ObjectTransformer
 */
class JmsArrayTransformerMapperTest extends TestCase
{
    public function testToResource()
    {
        $object = new \stdClass();

        $transformer = $this->createMock(ArrayTransformerInterface::class);
        $idHandler   = $this->createMock(IdentifierHandlerInterface::class);
        $typeHandler = $this->createMock(TypeHandlerInterface::class);

        $transformer->expects($this->once())
            ->method('toArray')
            ->with($object)
            ->willReturn(['test' => 'qwerty']);

        $idHandler->expects($this->once())
            ->method('getIdentifier')
            ->with($object)
            ->willReturn('123');

        $typeHandler->expects($this->once())
            ->method('getType')
            ->willReturn($object)
            ->willReturn('stdClass');

        $mapper   = new JmsArrayTransformerMapper($idHandler, $typeHandler, $transformer);
        $resource = $mapper->toResource($object);

        $this->assertInstanceOf(ResourceObject::class, $resource);
        $this->assertSame('123',$resource->getId());
        $this->assertSame('stdClass', $resource->getType());
        $this->assertSame(['test' => 'qwerty'], $resource->getAttributes());
    }

    public function testFromResource()
    {
        $object = new \stdClass();

        $transformer = $this->createMock(ArrayTransformerInterface::class);
        $idHandler   = $this->createMock(IdentifierHandlerInterface::class);
        $typeHandler = $this->createMock(TypeHandlerInterface::class);

        $transformer->expects($this->once())
            ->method('fromArray')
            ->with(
                ['test' => 'qwerty'],
                'stdClass',
                $this->isInstanceOf(DeserializationContext::class)
            );

        $idHandler->expects($this->once())
            ->method('setIdentifier')
            ->with($object, '123');

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('getId')
            ->willReturn('123');

        $resource->expects($this->once())
            ->method('getAttributes')
            ->willReturn(['test' => 'qwerty']);

        $mapper = new JmsArrayTransformerMapper($idHandler, $typeHandler, $transformer);
        $mapper->fromResource($object, $resource);
    }
}