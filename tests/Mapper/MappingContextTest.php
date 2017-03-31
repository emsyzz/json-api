<?php

namespace Mikemirten\Component\JsonApi\Mapper;

use Mikemirten\Component\JsonApi\Mapper\Definition\DefinitionInterface;
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
        $definition = $this->createMock(DefinitionInterface::class);

        $context = new MappingContext($mapper, $definition);

        $this->assertSame($mapper, $context->getMapper());
        $this->assertSame($definition, $context->getDefinition());
    }
}