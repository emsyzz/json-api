<?php

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\Behaviour\IncludedResourcesAwareInterface;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;
use PHPUnit\Framework\TestCase;

/**
 * @group hydrator
 */
class IncludedExtensionTest extends TestCase
{
    public function testSupports()
    {
        $extension = new IncludedExtension();

        $this->assertSame(['included'], $extension->supports());
    }

    public function testEmptyIncluded()
    {
        $object = $this->createMock(IncludedResourcesAwareInterface::class);

        $object->expects($this->never())
            ->method('addIncludedResource');

        $hydrator  = $this->createMock(DocumentHydrator::class);
        $extension = new IncludedExtension();

        $extension->hydrate($object, [], $hydrator);
    }

    public function testIncluded()
    {
        $object = $this->createMock(IncludedResourcesAwareInterface::class);

        $object->expects($this->once())
            ->method('addIncludedResource')
            ->with($this->isInstanceOf(ResourceObject::class));

        $hydrator = $this->createMock(DocumentHydrator::class);

        $hydrator->expects($this->once())
            ->method('hydrateResource')
            ->with($this->isType('object'));

        $extension = new IncludedExtension();

        $extension->hydrate($object, [new \stdClass()], $hydrator);
    }
}