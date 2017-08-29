<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler;

use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
class DateTimeHandlerTest extends TestCase
{
    public function testToResourceObject()
    {
        $handler  = new DateTimeHandler();
        $datetime = new \DateTimeImmutable('2010-01-02');

        $result = $handler->toResource($datetime, '', ['Y-m-d']);

        $this->assertSame('2010-01-02', $result);
    }

    public function testToResourceString()
    {
        $handler = new DateTimeHandler();
        $result  = $handler->toResource('2010-02-01', '', ['Y-d-m']);

        $this->assertSame('2010-01-02', $result);
    }

    public function testFromResourceObject()
    {
        $object = new \DateTimeImmutable();

        $handler = new DateTimeHandler();
        $result  = $handler->fromResource($object, '', ['Y-d-m']);

        $this->assertInstanceOf('DateTimeInterface', $result);
        $this->assertSame($object, $result);
    }

    public function testFromResourceString()
    {
        $handler = new DateTimeHandler();
        $result  = $handler->fromResource('2010-01-02', '', ['Y-d-m']);

        $this->assertInstanceOf('DateTimeInterface', $result);
        $this->assertSame('2010-01-02', $result->format('Y-m-d'));
    }
}