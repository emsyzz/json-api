<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Doctrine\Common\Annotations\AnnotationReader;
use Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor\AnnotationProcessorInterface;
use Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor\Fixture;
use Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor\Fixture2;
use Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor\Fixture5;
use PHPUnit\Framework\TestCase;

include_once __DIR__ . '/AnnotationProcessor/Fixture.php';
include_once __DIR__ . '/AnnotationProcessor/Fixture2.php';
include_once __DIR__ . '/AnnotationProcessor/Fixture5.php';

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class AnnotationDefinitionProviderTest extends TestCase
{
    public function testDefinition()
    {
        $provider   = new AnnotationDefinitionProvider();
        $definition = $provider->getDefinition(Fixture::class);

        $this->assertInstanceOf(Definition::class, $definition);
        $this->assertSame(Fixture::class, $definition->getClass());
    }

    public function testInheritance()
    {
        $provider   = new AnnotationDefinitionProvider();
        $definition = $provider->getDefinition(Fixture2::class);

        $this->assertInstanceOf(Definition::class, $definition);
        $this->assertSame(Fixture2::class, $definition->getClass());
    }

    public function testTrait()
    {
        $provider   = new AnnotationDefinitionProvider();
        $definition = $provider->getDefinition(Fixture5::class);

        $this->assertInstanceOf(Definition::class, $definition);
        $this->assertSame(Fixture5::class, $definition->getClass());
    }

    public function testProcessor()
    {
        $provider  = new AnnotationDefinitionProvider();
        $processor = $this->createMock(AnnotationProcessorInterface::class);

        $processor->expects($this->once())
            ->method('process')
            ->with(
                $this->isInstanceOf(\ReflectionClass::class),
                $this->isInstanceOf(Definition::class)
            );

        $provider->registerProcessor($processor);
        $provider->getDefinition(Fixture::class);
    }
}