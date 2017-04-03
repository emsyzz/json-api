<?php

namespace Mikemirten\Component\JsonApi\Mapper;

use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Handler\IdentifierHandler\IdentifierHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\Handler\TypeHandler\TypeHandlerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper
 */
class MappingContextTest extends TestCase
{
    public function testContextData()
    {
        $mapper      = $this->createMock(ObjectMapper::class);
        $definition  = $this->createMock(Definition::class);
        $idHandler   = $this->createMock(IdentifierHandlerInterface::class);
        $typeHandler = $this->createMock(TypeHandlerInterface::class);

        $context = new MappingContext($mapper, $definition, $idHandler, $typeHandler);

        $this->assertSame($mapper, $context->getMapper());
        $this->assertSame($definition, $context->getDefinition());
        $this->assertSame($idHandler, $context->getIdentifierHandler());
        $this->assertSame($typeHandler, $context->getTypeHandler());
    }
}