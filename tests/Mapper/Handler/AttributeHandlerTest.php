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
            ->with('test', 'asdfgh');

        $attributeDefinition = $this->createAttribute('test', 'getTest');

        $definition = $this->createDefinition([$attributeDefinition]);

        $manager = $this->createMock(DataTypeManager::class);

        $manager->expects($this->once())
            ->method('toResource')
            ->with($attributeDefinition, 'qwerty')
            ->willReturn('asdfgh');

        $context = $this->createMappingContext($definition);
        $handler = new AttributeHandler($manager);

        $handler->toResource($object, $resource, $context);
    }

    public function testNullToResource()
    {
        $object = new class
        {
            public function getTest() {}
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->never())
            ->method('setAttribute');

        $attributeDefinition = $this->createAttribute('test', 'getTest');

        $attributeDefinition->method('getProcessNull')
            ->willReturn(false);

        $definition = $this->createDefinition([$attributeDefinition]);

        $manager = $this->createMock(DataTypeManager::class);

        $manager->expects($this->never())
            ->method('toResource');

        $context = $this->createMappingContext($definition);
        $handler = new AttributeHandler($manager);

        $handler->toResource($object, $resource, $context);
    }

    public function testProcessNullToResource()
    {
        $object = new class
        {
            public function getTest() {}
        };

        $resource = $this->createMock(ResourceObject::class);

        $resource->expects($this->once())
            ->method('setAttribute')
            ->with('test', 0);

        $attributeDefinition = $this->createAttribute('test', 'getTest');

        $attributeDefinition->method('getProcessNull')
            ->willReturn(true);

        $definition = $this->createDefinition([$attributeDefinition]);

        $manager = $this->createMock(DataTypeManager::class);

        $manager->expects($this->once())
            ->method('toResource')
            ->with($attributeDefinition, null)
            ->willReturn(0);

        $context = $this->createMappingContext($definition);
        $handler = new AttributeHandler($manager);

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

        $attributeDefinition = $this->createAttributeSetContext('test', 'setTest');

        $definition = $this->createDefinition([$attributeDefinition]);

        $manager = $this->createMock(DataTypeManager::class);

        $manager->expects($this->once())
            ->method('fromResource')
            ->with($attributeDefinition, 12345)
            ->willReturn(45678);

        $context = $this->createMappingContext($definition);
        $handler = new AttributeHandler($manager);

        $handler->fromResource($object, $resource, $context);

        $this->assertSame(45678, $object->value);
    }

    public function testNullFromResource()
    {
        $object = new class
        {
            public $value = 12345;

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
            ->willReturn(null);

        $attributeDefinition = $this->createAttributeSetContext('test', 'setTest');

        $attributeDefinition->method('getProcessNull')
            ->willReturn(false);

        $definition = $this->createDefinition([$attributeDefinition]);

        $manager = $this->createMock(DataTypeManager::class);

        $manager->expects($this->never())
            ->method('fromResource');

        $context = $this->createMappingContext($definition);
        $handler = new AttributeHandler($manager);

        $handler->fromResource($object, $resource, $context);

        $this->assertSame(12345, $object->value);
    }

    public function testProcessNullFromResource()
    {
        $object = new class
        {
            public $value = 12345;

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
            ->willReturn(null);

        $attributeDefinition = $this->createAttributeSetContext('test', 'setTest');

        $attributeDefinition->method('getProcessNull')
            ->willReturn(true);

        $definition = $this->createDefinition([$attributeDefinition]);

        $manager = $this->createMock(DataTypeManager::class);

        $manager->expects($this->once())
            ->method('fromResource')
            ->with($attributeDefinition, null)
            ->willReturn(0);

        $context = $this->createMappingContext($definition);
        $handler = new AttributeHandler($manager);

        $handler->fromResource($object, $resource, $context);

        $this->assertSame(0, $object->value);
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

            $attribute->expects($this->atLeastOnce())
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

        $attribute->method('getSetter')
            ->willReturn($setter);

        if ($type !== null) {
            $attribute->expects($this->atLeastOnce())
                ->method('hasType')
                ->willReturn(true);

            $attribute->expects($this->atLeastOnce())
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