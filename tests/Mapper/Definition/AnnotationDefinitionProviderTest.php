<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Link as LinkAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Attribute as AttributeAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Relationship as RelationshipAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\ResourceIdentifier;
use PHPUnit\Framework\TestCase;

include __DIR__ . '/Fixture.php';
include __DIR__ . '/Fixture2.php';
include __DIR__ . '/Fixture3.php';
include __DIR__ . '/Fixture4.php';
include __DIR__ . '/Fixture5.php';

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class AnnotationDefinitionProviderTest extends TestCase
{
    public function testDefinition()
    {
        $reader = $this->createReader();

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $this->assertInstanceOf(Definition::class, $definition);
        $this->assertSame(Fixture::class, $definition->getClass());
    }

    public function testResourceType()
    {
        $resourceAnnotation = new ResourceIdentifier();
        $resourceAnnotation->type = 'qwerty';

        $reader = $this->createReader([$resourceAnnotation]);

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $this->assertTrue($definition->hasType());
        $this->assertSame('qwerty', $definition->getType());
    }

    /**
     * Integration test with real doctrine's reader
     *
     * @depends testResourceType
     */
    public function testIntegrationWithReaderResourceType()
    {
        $reader = new AnnotationReader();

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $this->assertTrue($definition->hasType());
        $this->assertSame('resource_type', $definition->getType());
    }

    public function testDefinitionLink()
    {
        $linkAnnotation = new LinkAnnotation();

        $linkAnnotation->name       = 'definition_link';
        $linkAnnotation->resource   = 'repository_name.link_name';
        $linkAnnotation->parameters = ['param_name' => 'param_value'];
        $linkAnnotation->metadata   = ['meta_name' => 'meta_value'];

        $reader = $this->createReader([$linkAnnotation]);

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $this->assertArrayHasKey('definition_link', $definition->getLinks());

        $link = $definition->getLinks()['definition_link'];

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

    /**
     * Integration test with real doctrine's reader
     *
     * @depends testDefinitionLink
     */
    public function testIntegrationWithReaderDefinitionLink()
    {
        $reader = new AnnotationReader();

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $this->assertArrayHasKey('definition_link', $definition->getLinks());

        $link = $definition->getLinks()['definition_link'];

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

    public function testPropertyAttribute()
    {
        $annotation = new AttributeAnnotation();
        $annotation->type = 'datetime(Y-m-d, 123)';

        $reader = $this->createReader([], [$annotation]);

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture3::class);

        $this->assertArrayHasKey('test', $definition->getAttributes());

        $attribute = $definition->getAttributes()['test'];

        $this->assertInstanceOf(Attribute::class, $attribute);
        $this->assertSame('test', $attribute->getName());
        $this->assertSame('getTest', $attribute->getGetter());
        $this->assertTrue($attribute->hasSetter());
        $this->assertSame('setTest', $attribute->getSetter());
        $this->assertSame('datetime', $attribute->getType());
        $this->assertSame(['Y-m-d', '123'], $attribute->getTypeParameters());
        $this->assertSame('test', $attribute->getPropertyName());
    }

    public function testPropertyAttributeIntegrationWithReader()
    {
        $reader = new AnnotationReader();

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture3::class);

        $this->assertArrayHasKey('test', $definition->getAttributes());

        $attribute = $definition->getAttributes()['test'];

        $this->assertInstanceOf(Attribute::class, $attribute);
        $this->assertSame('test', $attribute->getName());
        $this->assertTrue($attribute->hasSetter());
        $this->assertSame('setTest', $attribute->getSetter());
        $this->assertSame('datetime', $attribute->getType());
        $this->assertSame(['Y-m-d', '123'], $attribute->getTypeParameters());
        $this->assertSame('test', $attribute->getPropertyName());
    }

    public function testMethodAttribute()
    {
        $annotation = new AttributeAnnotation();
        $annotation->type = 'datetime(Y-m-d, 123)';

        $reader = $this->createReader([], [], [$annotation]);

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture4::class);

        $this->assertArrayHasKey('test', $definition->getAttributes());

        $attribute = $definition->getAttributes()['test'];

        $this->assertInstanceOf(Attribute::class, $attribute);
        $this->assertSame('test', $attribute->getName());
        $this->assertSame('getTest', $attribute->getGetter());
        $this->assertSame('datetime', $attribute->getType());
        $this->assertSame(['Y-m-d', '123'], $attribute->getTypeParameters());
        $this->assertFalse($attribute->hasPropertyName());
    }

    public function testMethodAttributeIntegrationWithReader()
    {
        $reader = new AnnotationReader();

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture4::class);

        $this->assertArrayHasKey('test', $definition->getAttributes());

        $attribute = $definition->getAttributes()['test'];

        $this->assertInstanceOf(Attribute::class, $attribute);
        $this->assertSame('test', $attribute->getName());
        $this->assertSame('getTest', $attribute->getGetter());
        $this->assertSame('datetime', $attribute->getType());
        $this->assertSame(['Y-m-d', '123'], $attribute->getTypeParameters());
        $this->assertFalse($attribute->hasPropertyName());
    }

    public function testRelation()
    {
        $annotation = new RelationshipAnnotation();
        $annotation->type = 'many';

        $reader = $this->createReader([], [$annotation]);

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $relationship = $definition->getRelationships()['test'];

        $this->assertInstanceOf(Relationship::class, $relationship);
        $this->assertSame('test', $relationship->getName());
        $this->assertTrue($relationship->isCollection());
        $this->assertSame('getTest', $relationship->getGetter());
        $this->assertSame('test', $relationship->getPropertyName());
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

        $reader = $this->createReader([], [$annotation]);

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $link = $definition->getRelationships()['test_relation']->getLinks()['relation_link'];

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

    /**
     * Integration test with real doctrine's reader
     *
     * @depends testRelation
     */
    public function testIntegrationWithReaderRelation()
    {
        $reader = new AnnotationReader();

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $relationship = $definition->getRelationships()['test'];

        $this->assertInstanceOf(Relationship::class, $relationship);
        $this->assertSame('test', $relationship->getName());
        $this->assertTrue($relationship->isCollection());
        $this->assertSame('getTest', $relationship->getGetter());
    }

    /**
     * Integration test with real doctrine's reader
     *
     * @depends testRelationLink
     */
    public function testIntegrationWithReaderRelationLink()
    {
        $reader = new AnnotationReader();

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $link = $definition->getRelationships()['test']->getLinks()['relation_link'];

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

    public function testDataControl()
    {
        $annotation = new RelationshipAnnotation();

        $annotation->name        = 'test_relation';
        $annotation->dataAllowed = true;
        $annotation->dataLimit   = 1000;

        $reader = $this->createReader([], [$annotation]);

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $relationship = $definition->getRelationships()['test_relation'];

        $this->assertTrue($relationship->isDataIncluded());
        $this->assertSame(1000, $relationship->getDataLimit());
    }

    /**
     * Integration test with real doctrine's reader
     *
     * @depends testDataControl
     */
    public function testIntegrationWithReaderDataControl()
    {
        $reader = new AnnotationReader();

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $relationship = $definition->getRelationships()['test'];

        $this->assertTrue($relationship->isDataIncluded());
        $this->assertSame(1000, $relationship->getDataLimit());
    }

    public function testInheritance()
    {
        $reader = $this->createMock(Reader::class);

        $reader->expects($this->exactly(2))
            ->method('getClassAnnotations')
            ->with($this->isInstanceOf('ReflectionClass'))
            ->willReturn([]);

        $reader->method('getPropertyAnnotations')
            ->with($this->isInstanceOf('ReflectionProperty'))
            ->willReturn([]);

        $reader->method('getMethodAnnotations')
            ->with($this->isInstanceOf('ReflectionMethod'))
            ->willReturn([]);

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture2::class);

        $this->assertInstanceOf(Definition::class, $definition);
        $this->assertSame(Fixture2::class, $definition->getClass());
    }

    public function testTrait()
    {
        $reader = $this->createMock(Reader::class);

        $reader->expects($this->exactly(2))
            ->method('getClassAnnotations')
            ->with($this->isInstanceOf('ReflectionClass'))
            ->willReturn([]);

        $reader->method('getPropertyAnnotations')
            ->with($this->isInstanceOf('ReflectionProperty'))
            ->willReturn([]);

        $reader->method('getMethodAnnotations')
            ->with($this->isInstanceOf('ReflectionMethod'))
            ->willReturn([]);

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture5::class);

        $this->assertInstanceOf(Definition::class, $definition);
        $this->assertSame(Fixture5::class, $definition->getClass());
    }

    /**
     * Integration test with real doctrine's reader
     *
     * @depends testResourceType
     */
    public function testIntegrationWithReaderInheritance()
    {
        $reader = new AnnotationReader();

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $this->assertTrue($definition->hasType());
        $this->assertSame('resource_type', $definition->getType());
    }

    /**
     * Create mock of annotation reader
     *
     * @param  array $classAnnotations
     * @param  array $propertyAnnotations
     * @param  array $methodAnnotations
     * @return Reader
     */
    protected function createReader(array $classAnnotations = [], array $propertyAnnotations = [], array $methodAnnotations = []): Reader
    {
        $reader = $this->createMock(Reader::class);

        $reader->expects($this->once())
            ->method('getClassAnnotations')
            ->with($this->isInstanceOf('ReflectionClass'))
            ->willReturn($classAnnotations);

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
}