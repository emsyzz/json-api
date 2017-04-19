<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler\LinkHandler;

use Mikemirten\Component\JsonApi\Document\LinkObject;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\Link as LinkDefinition;
use Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository\Link as LinkData;
use Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository\RepositoryInterface;
use Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository\RepositoryProvider;
use Mikemirten\Component\JsonApi\Mapper\Handler\PropertyAccessor\PropertyAccessorInterface;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
class LinkHandlerTest extends TestCase
{
    public function testHandleLinks()
    {
        $object      = new \stdClass();
        $scopeObject = new \ArrayIterator([]);

        $provider   = $this->createMock(RepositoryProvider::class);
        $accessor   = $this->createMock(PropertyAccessorInterface::class);
        $resource   = $this->createMock(ResourceObject::class);
        $repository = $this->createMock(RepositoryInterface::class);
        $linkData   = $this->createMock(LinkData::class);

        $linkData->expects($this->once())
            ->method('getReference')
            ->willReturn('http://test.com');

        $repository->expects($this->once())
            ->method('getLink')
            ->with(
                'test_link',
                [
                    'test_parameter1' => 'qwerty',
                    'test_parameter2' => 'test_value',
                    'test_parameter3' => 'test_value2'
                ]
            )
            ->willReturn($linkData);

        $provider->expects($this->once())
            ->method('getRepository')
            ->with('test_repository')
            ->willReturn($repository);

        $accessor->expects($this->at(0))
            ->method('getValue')
            ->with($object, 'test_property')
            ->willReturn('test_value');

        $accessor->expects($this->at(1))
            ->method('getValue')
            ->with($scopeObject, 'test_property2')
            ->willReturn('test_value2');

        $resource->expects($this->once())
            ->method('setLink')
            ->with(
                'test',
                $this->isInstanceOf(LinkObject::class)
            )
            ->willReturnCallback(function(string $name, LinkObject $link) {
                $this->assertSame('http://test.com', $link->getReference());
            });

        $link = $this->createLinkDefinition([
            'test_parameter1' => 'qwerty',
            'test_parameter2' => '@test_property',
            'test_parameter3' => '@test_object:test_property2',
        ]);

        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('getLinks')
            ->willReturn([$link]);

        $handler = new LinkHandler($provider, $accessor);

        $handler->handleLinks($object, $definition, $resource, [
            'test_object' => $scopeObject
        ]);
    }

    /**
     * @depends testHandleLinks
     */
    public function testToResource()
    {
        $object = new \stdClass();

        $provider   = $this->createMock(RepositoryProvider::class);
        $accessor   = $this->createMock(PropertyAccessorInterface::class);
        $resource   = $this->createMock(ResourceObject::class);
        $context    = $this->createMock(MappingContext::class);
        $repository = $this->createMock(RepositoryInterface::class);
        $linkData   = $this->createMock(LinkData::class);

        $linkData->expects($this->once())
            ->method('getReference')
            ->willReturn('http://test.com');

        $repository->expects($this->once())
            ->method('getLink')
            ->with(
                'test_link',
                ['test_parameter' => 'qwerty']
            )
            ->willReturn($linkData);

        $provider->expects($this->once())
            ->method('getRepository')
            ->with('test_repository')
            ->willReturn($repository);

        $resource->expects($this->once())
            ->method('setLink')
            ->with(
                'test',
                $this->isInstanceOf(LinkObject::class)
            )
            ->willReturnCallback(function(string $name, LinkObject $link) {
                $this->assertSame('http://test.com', $link->getReference());
            });

        $link = $this->createLinkDefinition([
            'test_parameter' => 'qwerty'
        ]);

        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('getLinks')
            ->willReturn([$link]);

        $context->method('getDefinition')
            ->willReturn($definition);

        $handler = new LinkHandler($provider, $accessor);

        $handler->toResource($object, $resource, $context);
    }

    protected function createLinkDefinition(array $parameters): LinkDefinition
    {
        $link = $this->createMock(LinkDefinition::class);

        $link->expects($this->once())
            ->method('getName')
            ->willReturn('test');

        $link->expects($this->once())
            ->method('getRepositoryName')
            ->willReturn('test_repository');

        $link->expects($this->once())
            ->method('getLinkName')
            ->willReturn('test_link');

        $link->expects($this->once())
            ->method('getParameters')
            ->willReturn($parameters);

        return $link;
    }
}