<?php

namespace Mikemirten\Component\JsonApi\Mapper\TypeHandler;

use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\TypeHandler
 */
class ClassBasedTypeHandlerTest extends TestCase
{
    public function testFullName()
    {
        $handler = new ClassBasedTypeHandler(true, '.');

        $this->assertSame(
            'Mikemirten.Component.JsonApi.Mapper.TypeHandler.ClassBasedTypeHandler',
            $handler->getType($handler)
        );
    }

    public function testShortName()
    {
        $handler = new ClassBasedTypeHandler(false);

        $this->assertSame(
            'ClassBasedTypeHandler',
            $handler->getType($handler)
        );
    }
}