<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor\ClassProcessor;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class ClassProcessorTest extends TestCase
{
    public function testProcessType()
    {
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('setType')
            ->with('blog.user');

        $processor = new ClassProcessor();
        $processor->process(['type' => 'blog.user'], $definition);
    }

    public function testProcessLinks()
    {
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addLink')
            ->with($this->isInstanceOf(Link::class))
            ->willReturnCallback(
                function(Link $link)
                {
                    $this->assertSame('self', $link->getName());
                    $this->assertSame('application', $link->getRepositoryName());
                    $this->assertSame('users', $link->getLinkName());
                    $this->assertSame(['id' => '@id'], $link->getParameters());
                    $this->assertSame(['method' => 'GET'], $link->getMetadata());
                }
            );

        $processor = new ClassProcessor();
        $processor->process(['links' => [
            'self' => [
                'resource'   => 'application.users',
                'parameters' => ['id' => '@id'],
                'metadata'   => ['method' => 'GET']
            ]
        ]], $definition);
    }
}