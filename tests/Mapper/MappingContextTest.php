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
        $mapper     = $this->createMock(ObjectMapper::class);
        $definition = $this->createMock(Definition::class);

        $context = new MappingContext($mapper, $definition);

        $this->assertSame($mapper, $context->getMapper());
        $this->assertSame($definition, $context->getDefinition());
    }
}