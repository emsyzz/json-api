<?php

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;
use PHPUnit\Framework\TestCase;

/**
 * @group hydrator
 */
class MetadataExtensionTest extends TestCase
{
    public function testSupports()
    {
        $handler = new MetadataExtension();

        $this->assertSame(['meta'], $handler->supports());
    }

    public function testHandle()
    {
        $object = $this->createMock(MetadataAwareInterface::class);

        $object->expects($this->once())
            ->method('setMetadataAttribute')
            ->with('test', 12345);

        $hydrator = $this->createMock(DocumentHydrator::class);
        $handler  = new MetadataExtension();

        $handler->hydrate($object, (object) ['test' => 12345], $hydrator);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Exception\InvalidDocumentException
     */
    public function testNoMetadataAware()
    {
        $hydrator = $this->createMock(DocumentHydrator::class);
        $handler  = new MetadataExtension();

        $handler->hydrate(new \stdClass(), (object) ['test' => 12345], $hydrator);
    }
}