<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Attribute as AttributeAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Attribute;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use PHPUnit\Framework\TestCase;

include_once __DIR__ . '/Fixture3.php';
include_once __DIR__ . '/Fixture4.php';

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor
 */
class AttributeProcessorTest extends TestCase
{
    public function testPropertyAttribute()
    {
        $annotation = new AttributeAnnotation();
        $annotation->type = 'datetime(Y-m-d, 123)';
        $annotation->many = true;
        $annotation->processNull = true;

        $reader = $this->createReader([$annotation]);

        $reflection = new \ReflectionClass(Fixture3::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addAttribute')
            ->with($this->isInstanceOf(Attribute::class))
            ->willReturnCallback(
                function(Attribute $attribute)
                {
                    $this->assertSame('test', $attribute->getName());
                    $this->assertSame('getTest', $attribute->getGetter());
                    $this->assertTrue($attribute->hasSetter());
                    $this->assertSame('setTest', $attribute->getSetter());
                    $this->assertSame('datetime', $attribute->getType());
                    $this->assertTrue($attribute->isMany());
                    $this->assertTrue($attribute->getProcessNull());
                    $this->assertSame(['Y-m-d', '123'], $attribute->getTypeParameters());
                    $this->assertSame('test', $attribute->getPropertyName());
                }
            );

        $processor = new AttributeProcessor($reader);
        $processor->process($reflection, $definition);
    }

    public function testPropertyAttributeIntegrationWithReader()
    {
        $reader = new AnnotationReader();

        $reflection = new \ReflectionClass(Fixture3::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addAttribute')
            ->with($this->isInstanceOf(Attribute::class))
            ->willReturnCallback(
                function(Attribute $attribute)
                {
                    $this->assertSame('test', $attribute->getName());
                    $this->assertSame('getTest', $attribute->getGetter());
                    $this->assertTrue($attribute->hasSetter());
                    $this->assertSame('setTest', $attribute->getSetter());
                    $this->assertSame('datetime', $attribute->getType());
                    $this->assertTrue($attribute->isMany());
                    $this->assertTrue($attribute->getProcessNull());
                    $this->assertSame(['Y-m-d', '123'], $attribute->getTypeParameters());
                    $this->assertSame('test', $attribute->getPropertyName());
                }
            );

        $processor = new AttributeProcessor($reader);
        $processor->process($reflection, $definition);
    }

    public function testMethodAttribute()
    {
        $annotation = new AttributeAnnotation();
        $annotation->type = 'datetime(Y-m-d, 123)';

        $reader = $this->createReader([], [$annotation]);

        $reflection = new \ReflectionClass(Fixture4::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addAttribute')
            ->with($this->isInstanceOf(Attribute::class))
            ->willReturnCallback(
                function(Attribute $attribute)
                {
                    $this->assertSame('test', $attribute->getName());
                    $this->assertSame('getTest', $attribute->getGetter());
                    $this->assertSame('datetime', $attribute->getType());
                    $this->assertSame(['Y-m-d', '123'], $attribute->getTypeParameters());
                    $this->assertFalse($attribute->hasPropertyName());
                }
            );

        $processor = new AttributeProcessor($reader);
        $processor->process($reflection, $definition);
    }

    public function testMethodAttributeIntegrationWithReader()
    {
        $reader = new AnnotationReader();

        $reflection = new \ReflectionClass(Fixture4::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addAttribute')
            ->with($this->isInstanceOf(Attribute::class))
            ->willReturnCallback(
                function(Attribute $attribute)
                {
                    $this->assertSame('test', $attribute->getName());
                    $this->assertSame('getTest', $attribute->getGetter());
                    $this->assertSame('datetime', $attribute->getType());
                    $this->assertSame(['Y-m-d', '123'], $attribute->getTypeParameters());
                    $this->assertFalse($attribute->hasPropertyName());
                }
            );

        $processor = new AttributeProcessor($reader);
        $processor->process($reflection, $definition);
    }

    /**
     * @dataProvider getValidTypeData
     *
     * @param string $definition
     * @param string $type
     * @param array  $parameters
     * @param bool   $many
     */
    public function testTypeParsing(string $definition, string $type, array $parameters, bool $many)
    {
        $annotation = new AttributeAnnotation();
        $annotation->type = $definition;

        $reader = $this->createReader([$annotation]);

        $reflection = new \ReflectionClass(Fixture::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addAttribute')
            ->with($this->isInstanceOf(Attribute::class))
            ->willReturnCallback(
                function(Attribute $attribute) use($type, $parameters, $many)
                {
                    $this->assertSame($type, $attribute->getType());
                    $this->assertSame($parameters, $attribute->getTypeParameters());
                    $this->assertSame($many, $attribute->isMany());
                }
            );

        $processor = new AttributeProcessor($reader);
        $processor->process($reflection, $definition);
    }

    /**
     * @dataProvider      getInvalidTypeData
     * @expectedException \LogicException
     *
     * @param string $definition
     */
    public function testTypeParsingException(string $definition)
    {
        $annotation = new AttributeAnnotation();
        $annotation->type = $definition;

        $reader = $this->createReader([$annotation]);

        $reflection = new \ReflectionClass(Fixture::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->never())
            ->method('addAttribute');

        $processor = new AttributeProcessor($reader);
        $processor->process($reflection, $definition);
    }

    /**
     * Create mock of annotation reader
     *
     * @param  array $propertyAnnotations
     * @param  array $methodAnnotations
     * @return Reader
     */
    protected function createReader(array $propertyAnnotations = [], array $methodAnnotations = []): Reader
    {
        $reader = $this->createMock(Reader::class);

        if (empty($propertyAnnotations)) {
            $reader->method('getPropertyAnnotations')
                ->with($this->isInstanceOf('ReflectionProperty'))
                ->willReturn([]);
        } else {
            $reader->expects($this->once())
                ->method('getPropertyAnnotations')
                ->with($this->isInstanceOf('ReflectionProperty'))
                ->willReturn($propertyAnnotations);
        }

        if (empty($methodAnnotations)) {
            $reader->method('getMethodAnnotations')
                ->with($this->isInstanceOf('ReflectionMethod'))
                ->willReturn([]);
        } else {
            $reader->expects($this->once())
                ->method('getMethodAnnotations')
                ->with($this->isInstanceOf('ReflectionMethod'))
                ->willReturn($methodAnnotations);
        }

        return $reader;
    }

    /**
     * @return array
     */
    public function getValidTypeData(): array
    {
        return [
            [ 'string',            'string',      [],         false ],
            [ 'string(1)',         'string',      ['1'],      false ],
            [ 'string[]',          'string',      [],         true  ],
            [ 'string(1)[]',       'string',      ['1'],      true  ],
            [ '_string',           '_string',     [],         false ],
            [ 'string2(1,2)',      'string2',     ['1', '2'], false ],
            [ 'string2[]',         'string2',     [],         true  ],
            [ 'string_2',          'string_2',    [],         false ],
            [ 'blog.user',         'blog.user',   [],         false ],
            [ 'blog.user(1)',      'blog.user',   ['1'],      false ],
            [ 'blog.user[]',       'blog.user',   [],         true  ],
            [ 'blog._user',        'blog._user',  [],         false ],
            [ 'blog.user2',        'blog.user2',  [],         false ],
            [ 'blog.user2(1,2)[]', 'blog.user2',  ['1', '2'], true  ],
            [ 'blog.user_2',       'blog.user_2', [],         false ],
        ];
    }

    /**
     * @return array
     */
    public function getInvalidTypeData(): array
    {
        return [[
            '', '.', '1',
            '2string',     'string.2',
            '.string',     'string.',
            'string[](1)', 'string[',
            'string.[]',   'string.()'
        ]];
    }
}