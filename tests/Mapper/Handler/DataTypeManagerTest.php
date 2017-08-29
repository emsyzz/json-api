<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Mapper\Definition\Attribute;
use Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler\DataTypeHandlerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
class DataTypeManagerTest extends TestCase
{
    public function testToResourceNotTyped()
    {
        $definition = $this->createNotTypedDefinition();

        $manager = new DataTypeManager();
        $value   = $manager->toResource($definition, 1);

        $this->assertSame(1, $value);
    }

    public function testToResourceNotTypedMany()
    {
        $definition = $this->createNotTypedDefinition(true);

        $manager = new DataTypeManager();
        $value   = $manager->toResource($definition, [1, 2]);

        $this->assertSame([1, 2], $value);
    }

    public function testFromResourceNotTyped()
    {
        $definition = $this->createNotTypedDefinition();

        $manager = new DataTypeManager();
        $value   = $manager->fromResource($definition, 1);

        $this->assertSame(1, $value);
    }

    public function testFromResourceNotTypedMany()
    {
        $definition = $this->createNotTypedDefinition(true);

        $manager = new DataTypeManager();
        $value   = $manager->fromResource($definition, [1, 2]);

        $this->assertInstanceOf('Traversable', $value);
        $this->assertSame([1, 2], iterator_to_array($value));
    }

    public function testToResourceHandler()
    {
        $definition = $this->createDefinition('test_type');

        $handler = $this->createMock(DataTypeHandlerInterface::class);

        $handler->method('supports')
            ->willReturn(['test_type']);

        $handler->expects($this->once())
            ->method('toResource')
            ->with('test_value', 'test_type', [])
            ->willReturn('processed_value');

        $manager = new DataTypeManager();
        $manager->registerDataTypeHandler($handler);

        $value = $manager->toResource($definition, 'test_value');

        $this->assertSame('processed_value', $value);
    }

    public function testManyToResourceHandler()
    {
        $definition = $this->createDefinition('test_type', true);

        $handler = $this->createMock(DataTypeHandlerInterface::class);

        $handler->method('supports')
            ->willReturn(['test_type']);

        $handler->expects($this->at(1))
            ->method('toResource')
            ->with('test_value1', 'test_type', [])
            ->willReturn('processed_value1');

        $handler->expects($this->at(2))
            ->method('toResource')
            ->with('test_value2', 'test_type', [])
            ->willReturn('processed_value2');

        $manager = new DataTypeManager();
        $manager->registerDataTypeHandler($handler);

        $value = $manager->toResource($definition, ['test_value1', 'test_value2']);

        $this->assertSame(['processed_value1', 'processed_value2'], $value);
    }

    public function testFromResourceHandler()
    {
        $definition = $this->createDefinition('test_type');

        $handler = $this->createMock(DataTypeHandlerInterface::class);

        $handler->method('supports')
            ->willReturn(['test_type']);

        $handler->expects($this->once())
            ->method('fromResource')
            ->with('test_value', 'test_type', [])
            ->willReturn('processed_value');

        $manager = new DataTypeManager();
        $manager->registerDataTypeHandler($handler);

        $value = $manager->fromResource($definition, 'test_value');

        $this->assertSame('processed_value', $value);
    }

    public function testManyFromResourceHandler()
    {
        $definition = $this->createDefinition('test_type', true);

        $handler = $this->createMock(DataTypeHandlerInterface::class);

        $handler->method('supports')
            ->willReturn(['test_type']);

        $handler->expects($this->at(1))
            ->method('fromResource')
            ->with('test_value1', 'test_type', [])
            ->willReturn('processed_value1');

        $handler->expects($this->at(2))
            ->method('fromResource')
            ->with('test_value2', 'test_type', [])
            ->willReturn('processed_value2');

        $manager = new DataTypeManager();
        $manager->registerDataTypeHandler($handler);

        $value = $manager->fromResource($definition, ['test_value1', 'test_value2']);

        $this->assertInstanceOf('Traversable', $value);
        $this->assertSame(['processed_value1', 'processed_value2'], iterator_to_array($value));
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Mapper\Handler\Exception\UnknownDataTypeException
     */
    public function testToResourceInvalidType()
    {
        $definition = $this->createMock(Attribute::class);

        $definition->expects($this->once())
            ->method('hasType')
            ->willReturn(true);

        $definition->expects($this->atLeastOnce())
            ->method('getType')
            ->willReturn('test');

        $manager = new DataTypeManager();
        $manager->toResource($definition, 1);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Mapper\Handler\Exception\UnknownDataTypeException
     */
    public function testFromResourceInvalidType()
    {
        $definition = $this->createMock(Attribute::class);

        $definition->expects($this->once())
            ->method('hasType')
            ->willReturn(true);

        $definition->expects($this->atLeastOnce())
            ->method('getType')
            ->willReturn('test');

        $manager = new DataTypeManager();
        $manager->fromResource($definition, 1);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Mapper\Handler\Exception\NotIterableAttribute
     */
    public function testToResourceNotTypedNotIterable()
    {
        $definition = $this->createNotTypedDefinition(true);

        $manager = new DataTypeManager();
        $value   = $manager->toResource($definition, 1);

        $this->assertSame(1, $value);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Mapper\Handler\Exception\NotIterableAttribute
     */
    public function testFroResourceNotTypedNotIterable()
    {
        $definition = $this->createNotTypedDefinition(true);

        $manager = new DataTypeManager();
        $value   = $manager->fromResource($definition, 1);

        $this->assertSame(1, $value);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Mapper\Handler\Exception\NotIterableAttribute
     */
    public function testToResourceHandlerNotIterable()
    {
        $definition = $this->createDefinition('test_type', true);

        $handler = $this->createMock(DataTypeHandlerInterface::class);

        $handler->method('supports')
            ->willReturn(['test_type']);

        $handler->expects($this->never())
            ->method('toResource');

        $manager = new DataTypeManager();
        $manager->registerDataTypeHandler($handler);

        $manager->toResource($definition, 'test_value');
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Mapper\Handler\Exception\NotIterableAttribute
     */
    public function testFromResourceHandlerNotIterable()
    {
        $definition = $this->createDefinition('test_type', true);

        $handler = $this->createMock(DataTypeHandlerInterface::class);

        $handler->method('supports')
            ->willReturn(['test_type']);

        $handler->expects($this->never())
            ->method('fromResource');

        $manager = new DataTypeManager();
        $manager->registerDataTypeHandler($handler);

        $manager->fromResource($definition, 'test_value');
    }

    /**
     * Create not-typed attribute's definition
     *
     * @param  bool $many
     * @return Attribute
     */
    protected function createNotTypedDefinition(bool $many = false): Attribute
    {
        $definition = $this->createMock(Attribute::class);

        $definition->expects($this->once())
            ->method('hasType')
            ->willReturn(false);

        $definition->expects($this->never())
            ->method('getType');

        $definition->expects($this->once())
            ->method('isMany')
            ->willReturn($many);

        return $definition;
    }

    /**
     * Create attribute's definition
     *
     * @param  string $type
     * @param  bool   $many
     * @return Attribute
     */
    protected function createDefinition(string $type, bool $many = false): Attribute
    {
        $definition = $this->createMock(Attribute::class);

        $definition->expects($this->once())
            ->method('hasType')
            ->willReturn(true);

        $definition->expects($this->atLeastOnce())
            ->method('getType')
            ->willReturn($type);

        $definition->expects($this->once())
            ->method('isMany')
            ->willReturn($many);

        return $definition;
    }
}