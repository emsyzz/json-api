<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

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
                [
                    'test_parameter1' => 'test_value',
                    'test_parameter2' => 'qwerty'
                ]
            )
            ->willReturn($linkData);

        $provider->expects($this->once())
            ->method('getRepository')
            ->with('test_repository')
            ->willReturn($repository);

        $accessor->expects($this->once())
            ->method('getValue')
            ->with($object, 'test_property')
            ->willReturn('test_value');

        $resource->expects($this->once())
            ->method('setLink')
            ->with(
                'test',
                $this->isInstanceOf(LinkObject::class)
            )
            ->willReturnCallback(function(string $name, LinkObject $link) {
                $this->assertSame('http://test.com', $link->getReference());
            });

        $link = $this->createLinkDefinition();

        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('getLinks')
            ->willReturn([$link]);

        $context->method('getDefinition')
            ->willReturn($definition);

        $handler = new LinkHandler($provider, $accessor);

        $handler->toResource($object, $resource, $context);
    }

    protected function createLinkDefinition(): LinkDefinition
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
            ->willReturn([
                'test_parameter1' => '@test_property',
                'test_parameter2' => 'qwerty'
            ]);

        return $link;
    }
}