<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class MergedProviderTest extends TestCase
{
    public function testGetDefinition()
    {
        $provider = new MergedProvider();

        $definition = $provider->getDefinition('stdClass');

        $this->assertInstanceOf(Definition::class, $definition);
        $this->assertSame('stdClass', $definition->getClass());
    }

    public function testGetMergedDefinition()
    {
        $testDefinition = $this->createMock(Definition::class);
        $testProvider   = $this->createMock(DefinitionProviderInterface::class);

        $testDefinition->method('hasType')
            ->willReturn(true);

        $testDefinition->method('getType')
            ->willReturn('test_type');

        $testProvider->expects($this->once())
            ->method('getDefinition')
            ->with('stdClass')
            ->willReturn($testDefinition);

        $provider = new MergedProvider();
        $provider->addProvider($testProvider);

        $definition = $provider->getDefinition('stdClass');
        $this->assertTrue($definition->hasType());
        $this->assertSame('test_type', $definition->getType());
    }
}