<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Link as LinkAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Relationship as RelationshipAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\Link;
use Mikemirten\Component\JsonApi\Mapper\Definition\Relationship;
use PHPUnit\Framework\TestCase;

include_once __DIR__ . '/Fixture.php';

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\AnnotationProcessor
 */
class RelationshipProcessorTest extends TestCase
{
    public function testRelation()
    {
        $annotation = new RelationshipAnnotation();
        $annotation->type = 'many';
        $annotation->dataAllowed = true;
        $annotation->dataLimit   = 1000;

        $reader = $this->createReader([$annotation]);

        $reflection = new \ReflectionClass(Fixture::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addRelationship')
            ->with($this->isInstanceOf(Relationship::class))
            ->willReturnCallback(
                function(Relationship $relationship)
                {
                    $this->assertSame('test', $relationship->getName());
                    $this->assertTrue($relationship->isCollection());
                    $this->assertSame('getTest', $relationship->getGetter());
                    $this->assertSame('test', $relationship->getPropertyName());
                    $this->assertTrue($relationship->isDataIncluded());
                    $this->assertSame(1000, $relationship->getDataLimit());
                }
            );

        $processor = new RelationshipProcessor($reader);
        $processor->process($reflection, $definition);
    }

    public function testRelationLink()
    {
        $linkAnnotation = new LinkAnnotation();

        $linkAnnotation->name       = 'relation_link';
        $linkAnnotation->resource   = 'repository_name.link_name';
        $linkAnnotation->parameters = ['param_name' => 'param_value'];
        $linkAnnotation->metadata   = ['meta_name' => 'meta_value'];

        $annotation = new RelationshipAnnotation();

        $annotation->name  = 'test_relation';
        $annotation->links = [$linkAnnotation];

        $reader = $this->createReader([$annotation]);

        $reflection = new \ReflectionClass(Fixture::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addRelationship')
            ->with($this->isInstanceOf(Relationship::class))
            ->willReturnCallback(
                function(Relationship $relationship)
                {
                    $links = $relationship->getLinks();

                    $this->assertCount(1, $links);
                    $this->assertArrayHasKey('relation_link', $links);

                    $link = $links['relation_link'];

                    $this->assertInstanceOf(Link::class, $link);
                    $this->assertSame('relation_link', $link->getName());
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

        $processor = new RelationshipProcessor($reader);
        $processor->process($reflection, $definition);
    }

    /**
     * Integration test with real doctrine's reader
     *
     * @depends testRelation
     */
    public function testIntegrationWithReaderRelation()
    {
        $reader = new AnnotationReader();

        $reflection = new \ReflectionClass(Fixture::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addRelationship')
            ->with($this->isInstanceOf(Relationship::class))
            ->willReturnCallback(
                function(Relationship $relationship)
                {
                    $this->assertSame('test', $relationship->getName());
                    $this->assertTrue($relationship->isCollection());
                    $this->assertSame('getTest', $relationship->getGetter());
                    $this->assertSame('test', $relationship->getPropertyName());
                    $this->assertTrue($relationship->isDataIncluded());
                    $this->assertSame(1000, $relationship->getDataLimit());
                }
            );

        $processor = new RelationshipProcessor($reader);
        $processor->process($reflection, $definition);
    }

    /**
     * Integration test with real doctrine's reader
     *
     * @depends testRelationLink
     */
    public function testIntegrationWithReaderRelationLink()
    {
        $reader = new AnnotationReader();

        $reflection = new \ReflectionClass(Fixture::class);
        $definition = $this->createMock(Definition::class);

        $definition->expects($this->once())
            ->method('addRelationship')
            ->with($this->isInstanceOf(Relationship::class))
            ->willReturnCallback(
                function(Relationship $relationship)
                {
                    $links = $relationship->getLinks();

                    $this->assertCount(1, $links);
                    $this->assertArrayHasKey('relation_link', $links);

                    $link = $links['relation_link'];

                    $this->assertInstanceOf(Link::class, $link);
                    $this->assertSame('relation_link', $link->getName());
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

        $processor = new RelationshipProcessor($reader);
        $processor->process($reflection, $definition);
    }

    /**
     * Create mock of annotation reader
     *
     * @param  array $propertyAnnotations
     * @return Reader
     */
    protected function createReader(array $propertyAnnotations = []): Reader
    {
        $reader = $this->createMock(Reader::class);

        $reader->expects($this->once())
            ->method('getPropertyAnnotations')
            ->with($this->isInstanceOf('ReflectionProperty'))
            ->willReturn($propertyAnnotations);

        return $reader;
    }
}