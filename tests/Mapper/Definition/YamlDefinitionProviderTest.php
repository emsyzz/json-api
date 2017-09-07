<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor\ConfigurationProcessorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\NodeInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Parser;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class YamlDefinitionProviderTest extends TestCase
{
    public function testGetDefinition()
    {
        $schema = $this->createMock(NodeInterface::class);
        $parser = $this->createMock(Parser::class);

        $parser->expects($this->once())
            ->method('parse')
            ->with('type: "user"')
            ->willReturn(['type' => 'user']);

        $configProcessor = $this->createMock(Processor::class);

        $configProcessor->expects($this->once())
            ->method('process', ['type' => 'user'])
            ->with($schema)
            ->willReturn(['type' => 'user']);

        $configuration = $this->createMock(ConfigurationInterface::class);
        $treeBuilder   = $this->createMock(TreeBuilder::class);

        $treeBuilder->expects($this->once())
            ->method('buildTree')
            ->willReturn($schema);

        $configuration->expects($this->once())
            ->method('getConfigTreeBuilder')
            ->willReturn($treeBuilder);

        $processor = $this->createMock(ConfigurationProcessorInterface::class);

        $processor->expects($this->once())
            ->method('process')
            ->with(
                ['type' => 'user'],
                $this->isInstanceOf(Definition::class)
            );

        $provider = new YamlDefinitionProvider(__DIR__, $parser, $configProcessor, $configuration);
        $provider->registerProcessor($processor);

        $definition = $provider->getDefinition('fixture');

        $this->assertInstanceOf(Definition::class, $definition);
        $this->assertSame('fixture', $definition->getClass());
        $this->assertSame('user', $definition->getType());
    }
}