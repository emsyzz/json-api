<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Mikemirten\Component\JsonApi\Mapper\Definition\Annotation\Relationship as RelationshipAnnotation;
use PHPUnit\Framework\TestCase;

include __DIR__ . '/Fixture.php';

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class AnnotationDefinitionProviderTest extends TestCase
{
    public function testDfinition()
    {
        $annotation = new RelationshipAnnotation();

        $annotation->name         = 'test_relation';
        $annotation->type         = 'many';
        $annotation->resourceType = 'Fixture';
        $annotation->getter       = 'getTest';
        $annotation->idProperty   = 'id';

        $reader = $this->createMock(Reader::class);

        $reader->expects($this->once())
            ->method('getPropertyAnnotation')
            ->with(
                $this->isInstanceOf(\ReflectionProperty::class),
                RelationshipAnnotation::class
            )
            ->willReturn($annotation);

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $this->assertInstanceOf(Definition::class, $definition);

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

    /**
     * Integration test with real doctrine's reader
     */
    public function testIntegrationWithReader()
    {
        $reader = new AnnotationReader();

        $provider   = new AnnotationDefinitionProvider($reader);
        $definition = $provider->getDefinition(Fixture::class);

        $this->assertInstanceOf(Definition::class, $definition);

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
}