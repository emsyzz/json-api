<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Definition;
use Mikemirten\Component\JsonApi\Mapper\Definition\Link;
use Mikemirten\Component\JsonApi\Mapper\Definition\Relationship;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor
 */
class RelationshipProcessorTest extends TestCase
{
    public function testRelation()
    {
        $definition = $this->createMock(Definition::class);

        $definition->method('getClass')
            ->willReturn('stdClass');

        $definition->expects($this->once())
            ->method('addRelationship')
            ->with($this->isInstanceOf(Relationship::class))
            ->willReturnCallback(
                function(Relationship $relationship)
                {
                    $this->assertSame('roles', $relationship->getName());
                    $this->assertFalse($relationship->isCollection());
                    $this->assertSame('getTest', $relationship->getGetter());
                    $this->assertFalse($relationship->isDataIncluded());
                    $this->assertSame(0, $relationship->getDataLimit());
                }
            );

        $processor = new RelationshipProcessor();
        $processor->process([
            'relationships' => [
                'roles' => [
                    'type'        => 'one',
                    'getter'      => 'getTest',
                    'dataAllowed' => false,
                    'dataLimit'   => 0
                ]
            ]
        ], $definition);
    }

    public function testRelationLink()
    {
        $definition = $this->createMock(Definition::class);

        $definition->method('getClass')
            ->willReturn('stdClass');

        $definition->expects($this->once())
            ->method('addRelationship')
            ->with($this->isInstanceOf(Relationship::class))
            ->willReturnCallback(
                function(Relationship $relationship)
                {
                    $links = $relationship->getLinks();

                    $this->assertCount(1, $links);
                    $this->assertArrayHasKey('self', $links);

                    $link = $links['self'];

                    $this->assertInstanceOf(Link::class, $link);
                    $this->assertSame('self', $link->getName());
                    $this->assertSame('application', $link->getRepositoryName());
                    $this->assertSame('users', $link->getLinkName());
                    $this->assertSame(
                        ['id' => '@id'],
                        $link->getParameters()
                    );
                    $this->assertSame(
                        ['method' => 'GET'],
                        $link->getMetadata()
                    );
                }
            );

        $processor = new RelationshipProcessor();
        $processor->process([
            'relationships' => [
                'roles' => [
                    'type'        => 'one',
                    'getter'      => 'getTest',
                    'dataAllowed' => false,
                    'dataLimit'   => 0,
                    'links'  => [
                        'self' => [
                            'resource'   => 'application.users',
                            'parameters' => ['id' => '@id'],
                            'metadata'   => ['method' => 'GET']
                        ]
                    ]
                ]
            ]
        ], $definition);
    }
}