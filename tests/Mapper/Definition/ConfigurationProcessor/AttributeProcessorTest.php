<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Attribute;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor
 */
class AttributeProcessorTest extends TestCase
{
    public function testPropertyAttribute()
    {
        $definition = $this->createMock(Definition::class);

        $definition->method('getClass')
            ->willReturn('stdClass');

        $definition->expects($this->once())
            ->method('addAttribute')
            ->with($this->isInstanceOf(Attribute::class))
            ->willReturnCallback(
                function(Attribute $attribute)
                {
                    $this->assertSame('firstName', $attribute->getName());
                    $this->assertSame('getFirstName', $attribute->getGetter());
                    $this->assertTrue($attribute->hasSetter());
                    $this->assertSame('setFirstName', $attribute->getSetter());
                    $this->assertSame('string', $attribute->getType());
                    $this->assertTrue($attribute->getProcessNull());
                }
            );

        $processor = new AttributeProcessor();
        $processor->process([
            'attributes' => [
                'firstName' => [
                    'type'        => 'string',
                    'getter'      => 'getFirstName',
                    'setter'      => 'setFirstName',
                    'processNull' => true
                ]
            ]
        ], $definition);
    }

    /**
     * @dataProvider getValidTypeData
     *
     * @param string $typeDefinition
     * @param string $type
     * @param array  $parameters
     * @param bool   $many
     */
    public function testTypeParsing(string $typeDefinition, string $type, array $parameters, bool $many)
    {
        $definition = $this->createMock(Definition::class);

        $definition->method('getClass')
            ->willReturn('stdClass');

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

        $processor = new AttributeProcessor();
        $processor->process([
            'attributes' => [
                'test' => [
                    'type'   => $typeDefinition,
                    'getter' => 'getTest'
                ]
            ]
        ], $definition);
    }

    /**
     * @dataProvider      getInvalidTypeData
     * @expectedException \LogicException
     *
     * @param string $typeDefinition
     */
    public function testTypeParsingException(string $typeDefinition)
    {
        $definition = $this->createMock(Definition::class);

        $definition->method('getClass')
            ->willReturn('stdClass');

        $definition->expects($this->never())
            ->method('addAttribute');

        $processor = new AttributeProcessor();
        $processor->process([
            'attributes' => [
                'firstName' => [
                    'type'   => $typeDefinition,
                    'getter' => 'getTest'
                ]
            ]
        ], $definition);
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