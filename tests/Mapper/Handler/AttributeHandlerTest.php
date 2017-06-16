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

    public function testFromResource()
    {
        $object = new class
        {
            public $value;

            public function setTest($value)
            {
                $this->value = $value;
            }
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('hasAttribute')
            ->with('test')
            ->willReturn(true);

        $resource->expects($this->once())
            ->method('getAttribute')
            ->with('test')
            ->willReturn(12345);

        $definition = $this->createDefinition([
            $this->createAttributeSetContext('test', 'setTest')
        ]);

        $context = $this->createMappingContext($definition);
        $handler = new AttributeHandler();

        $handler->fromResource($object, $resource, $context);

        $this->assertSame(12345, $object->value);
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

    public function testFromResourceGenericDataType()
    {
        $object = new class
        {
            public $value;

            public function setTest($value)
            {
                $this->value = $value;
            }
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('hasAttribute')
            ->with('test')
            ->willReturn(true);

        $resource->expects($this->once())
            ->method('getAttribute')
            ->with('test')
            ->willReturn(12345);

        $definition = $this->createDefinition([
            $this->createAttributeSetContext('test', 'setTest', 'string')
        ]);

        $context = $this->createMappingContext($definition);
        $handler = new AttributeHandler();

        $handler->fromResource($object, $resource, $context);

        $this->assertSame('12345', $object->value);
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

    public function testFromResourceDataTypeHandler()
    {
        $object = new class
        {
            public $value;

            public function setTest($value)
            {
                $this->value = $value;
            }
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('hasAttribute')
            ->with('test')
            ->willReturn(true);

        $resource->expects($this->once())
            ->method('getAttribute')
            ->with('test')
            ->willReturn('1996-06-04');

        $definition = $this->createDefinition([
            $this->createAttributeSetContext('test', 'setTest', 'datetime', ['Y-m-d'])
        ]);

        $context = $this->createMappingContext($definition);
        $value   = new \DateTimeImmutable();

        $typeHandler = $this->createMock(DataTypeHandlerInterface::class);

        $typeHandler->expects($this->once())
            ->method('supports')
            ->willReturn(['datetime']);

        $typeHandler->expects($this->once())
            ->method('fromResource')
            ->with('1996-06-04', ['Y-m-d'])
            ->willReturn($value);

        $handler = new AttributeHandler();
        $handler->registerDataTypeHandler($typeHandler);

        $handler->fromResource($object, $resource, $context);

        $this->assertSame($value, $object->value);
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

    /**
     * Create mock of attribute's definition
     *
     * @param  string $name
     * @param  string $setter
     * @param  string $type
     * @param  array  $typeParams
     * @return Attribute
     */
    protected function createAttributeSetContext(string $name, string $setter, string $type = null, array $typeParams = null): Attribute
    {
        $attribute = $this->createMock(Attribute::class);

        $attribute->expects($this->atLeastOnce())
            ->method('getName')
            ->willReturn($name);

        $attribute->expects($this->once())
            ->method('hasSetter')
            ->willReturn(true);

        $attribute->expects($this->once())
            ->method('getSetter')
            ->willReturn($setter);

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