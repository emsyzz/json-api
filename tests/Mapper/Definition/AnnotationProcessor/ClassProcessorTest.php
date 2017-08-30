<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Link as LinkAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\ResourceIdentifier;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\Link;
use PHPUnit\Framework\TestCase;

include_once __DIR__ . '/Fixture.php';

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor
 */
class ClassProcessorTest extends TestCase
{
    public function testResourceType()
    {
        $resourceAnnotation = new ResourceIdentifier();
        $resourceAnnotation->type = 'resource_type';

        $reader = $this->createReader([$resourceAnnotation]);

        $reflection = new \ReflectionClass(Fixture::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('setType')
            ->with('resource_type');

        $processor = new ClassProcessor($reader);
        $processor->process($reflection, $definition);
    }

    /**
     * Integration test with real doctrine's reader
     *
     * @depends testResourceType
     */
    public function testIntegrationWithReaderResourceType()
    {
        $reader = new AnnotationReader();

        $reflection = new \ReflectionClass(Fixture::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('setType')
            ->with('resource_type');

        $processor = new ClassProcessor($reader);
        $processor->process($reflection, $definition);
    }

    public function testDefinitionLink()
    {
        $linkAnnotation = new LinkAnnotation();

        $linkAnnotation->name       = 'definition_link';
        $linkAnnotation->resource   = 'repository_name.link_name';
        $linkAnnotation->parameters = ['param_name' => 'param_value'];
        $linkAnnotation->metadata   = ['meta_name' => 'meta_value'];

        $reader = $this->createReader([$linkAnnotation]);

        $reflection = new \ReflectionClass(Fixture::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addLink')
            ->with($this->isInstanceOf(Link::class))
            ->willReturnCallback(
                function(Link $link)
                {
                    $this->assertSame('definition_link', $link->getName());
                    $this->assertSame('repository_name', $link->getRepositoryName());
                    $this->assertSame('link_name', $link->getLinkName());
                    $this->assertSame(
                        ['param_name' => 'param_value'],
                        $link->getParameters()
                    );
                    $this->assertSame(
                        ['meta_name' => 'meta_value'],
                        $link->getMetadata()
                    );
                }
            );

        $processor = new ClassProcessor($reader);
        $processor->process($reflection, $definition);
    }

    /**
     * Integration test with real doctrine's reader
     *
     * @depends testDefinitionLink
     */
    public function testIntegrationWithReaderDefinitionLink()
    {
        $reader = new AnnotationReader();

        $reflection = new \ReflectionClass(Fixture::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addLink')
            ->with($this->isInstanceOf(Link::class))
            ->willReturnCallback(
                function(Link $link)
                {
                    $this->assertSame('definition_link', $link->getName());
                    $this->assertSame('repository_name', $link->getRepositoryName());
                    $this->assertSame('link_name', $link->getLinkName());
                    $this->assertSame(
                        ['param_name' => 'param_value'],
                        $link->getParameters()
                    );
                    $this->assertSame(
                        ['meta_name' => 'meta_value'],
                        $link->getMetadata()
                    );
                }
            );

        $processor = new ClassProcessor($reader);
        $processor->process($reflection, $definition);
    }

    /**
     * Create mock of annotation reader
     *
     * @param  array $classAnnotations
     * @return Reader
     */
    protected function createReader(array $classAnnotations = []): Reader
    {
        $reader = $this->createMock(Reader::class);

        $reader->expects($this->once())
            ->method('getClassAnnotations')
            ->with($this->isInstanceOf('ReflectionClass'))
            ->willReturn($classAnnotations);

        return $reader;
    }
}