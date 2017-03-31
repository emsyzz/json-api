<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler\TypeHandler;

use Mikemirten\Component\JsonApi\Mapper\MappingContext;
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
        $context = $this->createMock(MappingContext::class);


        $this->assertSame(
            'Mikemirten.Component.JsonApi.Mapper.Handler.TypeHandler.ClassBasedTypeHandler',
            $handler->getType($handler, $context)
        );
    }

    public function testShortName()
    {
        $handler = new ClassBasedTypeHandler(false);
        $context = $this->createMock(MappingContext::class);

        $this->assertSame(
            'ClassBasedTypeHandler',
            $handler->getType($handler, $context)
        );
    }
}