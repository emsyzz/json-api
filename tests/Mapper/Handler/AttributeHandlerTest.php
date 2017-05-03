<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\Definition\Attribute;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler\DataTypeHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\ObjectTransformer
 */
class AttributeHandlerTest extends TestCase
{
    public function testToResource()
    {
        $object = new class
        {
            public function getTest()
            {
                return 'qwerty';
            }
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setAttribute')
            ->with('test', 'qwerty');

        $definition = $this->createDefinition([
            $this->createAttribute('test', 'getTest')
        ]);

        $context = $this->createMappingContext($definition);
        $handler = new AttributeHandler();

        $handler->toResource($object, $resource, $context);
    }

    public function testToResourceGenericDataType()
    {
        $object = new class
        {
            public function getTest()
            {
                return 1;
            }
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setAttribute')
            ->with('test', true);

        $definition = $this->createDefinition([
            $this->createAttribute('test', 'getTest', 'boolean')
        ]);

        $context = $this->createMappingContext($definition);
        $handler = new AttributeHandler();

        $handler->toResource($object, $resource, $context);
    }

    public function testToResourceDataTypeHandler()
    {
        $object = new class
        {
            public function getTest()
            {
                return new \DateTime('1980-01-02');
            }
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setAttribute')
            ->with('test', '1980-01-02');

        $definition = $this->createDefinition([
            $this->createAttribute('test', 'getTest', 'datetime', ['Y-m-d'])
        ]);

        $context = $this->createMappingContext($definition);

        $typeHandler = $this->createMock(DataTypeHandlerInterface::class);

        $typeHandler->expects($this->once())
            ->method('supports')
            ->willReturn(['datetime']);

        $typeHandler->expects($this->once())
            ->method('toResource')
            ->with($this->isInstanceOf('DateTime'), ['Y-m-d'])
            ->willReturn('1980-01-02');

        $handler = new AttributeHandler();
        $handler->registerDataTypeHandler($typeHandler);

        $handler->toResource($object, $resource, $context);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp ~test~
     * @expectedExceptionMessageRegExp ~datetime~
     */
    public function testToResourceInvalidDateType()
    {
        $object = new class
        {
            public function getTest() {}
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->never())
            ->method('setAttribute');

        $definition = $this->createDefinition([
            $this->createAttribute('test', 'getTest', 'datetime')
        ]);

        $context = $this->createMappingContext($definition);
        $handler = new AttributeHandler();

        $handler->toResource($object, $resource, $context);
    }

    /**
     * Create mock of mapping context
     *
     * @param  Definition $definition
     * @return MappingContext
     */
    public function createMappingContext(Definition $definition): MappingContext
    {
        $context = $this->createMock(MappingContext::class);

        $context->expects($this->once())
            ->method('getDefinition')
            ->willReturn($definition);

        return $context;
    }

    /**
     * Create mock of mapping definition
     *
     * @param  array $attributes
     * @return Definition
     */
    public function createDefinition(array $attributes): Definition
    {
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('getAttributes')
            ->willReturn($attributes);

        return $definition;
    }

    /**
     * Create mock of attribute's definition
     *
     * @param  string $name
     * @param  string $getter
     * @param  string $type
     * @param  array  $typeParams
     * @return Attribute
     */
    protected function createAttribute(string $name, string $getter, string $type = null, array $typeParams = null): Attribute
    {
        $attribute = $this->createMock(Attribute::class);

        $attribute->expects($this->atLeastOnce())
            ->method('getName')
            ->willReturn($name);

        $attribute->expects($this->once())
            ->method('getGetter')
            ->willReturn($getter);

        if ($type !== null) {
            $attribute->expects($this->atLeastOnce())
                ->method('hasType')
                ->willReturn(true);

            $attribute->expects($this->once())
                ->method('getType')
                ->willReturn($type);
        }

        if ($typeParams !== null) {
            $attribute->expects($this->once())
                ->method('getTypeParameters')
                ->willReturn($typeParams);
        }

        return $attribute;
    }
}