<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\DeserializationContext;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\ObjectTransformer
 */
class JmsArrayTransformerHandlerTest extends TestCase
{
    public function testToResource()
    {
        $object = new \stdClass();

        $transformer = $this->createMock(ArrayTransformerInterface::class);

        $transformer->expects($this->once())
            ->method('toArray')
            ->with($object)
            ->willReturn(['test' => 'qwerty']);

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setAttribute')
            ->with('test', 'qwerty');

        $handler = new JmsArrayTransformerHandler($transformer);
        $context = $this->createMock(MappingContext::class);

        $handler->toResource($object, $resource, $context);
    }

    public function testFromResource()
    {
        $object = new \stdClass();

        $transformer = $this->createMock(ArrayTransformerInterface::class);

        $transformer->expects($this->once())
            ->method('fromArray')
            ->with(
                ['test' => 'qwerty'],
                'stdClass',
                $this->isInstanceOf(DeserializationContext::class)
            );

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('getAttributes')
            ->willReturn(['test' => 'qwerty']);

        $handler = new JmsArrayTransformerHandler($transformer);
        $context = $this->createMock(MappingContext::class);

        $handler->fromResource($object, $resource, $context);
    }
}