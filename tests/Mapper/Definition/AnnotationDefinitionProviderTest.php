<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Link as LinkAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Relationship as RelationshipAnnotation;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\ResourceIdentifier;
use PHPUnit\Framework\TestCase;

include __DIR__ . '/Fixture.php';

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

    public function testRelation()
    {
        $annotation = new RelationshipAnnotation();

        $annotation->name         = 'test_relation';
        $annotation->type         = 'many';
        $annotation->resourceType = 'Fixture';
        $annotation->getter       = 'getTest';
        $annotation->idProperty   = 'id';

        $reader = $this->createReader([], $annotation);

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $relationship = $definition->getRelationships()['test_relation'];

        $this->assertInstanceOf(Relationship::class, $relationship);
        $this->assertSame('test_relation', $relationship->getName());
        $this->assertTrue($relationship->isCollection());
        $this->assertSame('Fixture', $relationship->getResourceType());
        $this->assertTrue($relationship->hasResourceType());
        $this->assertSame('getTest', $relationship->getGetter());
        $this->assertTrue($relationship->hasIdentifierGetter());
        $this->assertSame('getId', $relationship->getIdentifierGetter());
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

        $reader = $this->createReader([], $annotation);

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
        $this->assertSame('Fixture', $relationship->getResourceType());
        $this->assertTrue($relationship->hasResourceType());
        $this->assertSame('getTest', $relationship->getGetter());
        $this->assertTrue($relationship->hasIdentifierGetter());
        $this->assertSame('getId', $relationship->getIdentifierGetter());
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

        $reader = $this->createReader([], $annotation);

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

    /**
     * Create mock of annotation reader
     *
     * @param  array                  $classAnnotations
     * @param  RelationshipAnnotation $relationshipAnnotation
     * @return Reader
     */
    protected function createReader(array $classAnnotations = [], RelationshipAnnotation $relationshipAnnotation = null): Reader
    {
        $reader = $this->createMock(Reader::class);

        $reader->expects($this->once())
            ->method('getClassAnnotations')
            ->with($this->isInstanceOf('ReflectionClass'))
            ->willReturn($classAnnotations);

        if ($relationshipAnnotation !== null) {
            $reader->expects($this->once())
                ->method('getPropertyAnnotation')
                ->with(
                    $this->isInstanceOf('ReflectionProperty'),
                    RelationshipAnnotation::class
                )
                ->willReturn($relationshipAnnotation);
        }

        return $reader;
    }
}