<?php

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\LinkObject;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;
use PHPUnit\Framework\TestCase;

/**
 * @group hydrator
 */
class LinksExtensionTest extends TestCase
{
    public function testSupports()
    {
        $handler = new LinksExtension();

        $this->assertSame(['links'], $handler->supports());
    }

    public function testHandle()
    {
        $object = $this->createMock(LinksAwareInterface::class);

        $object->expects($this->exactly(2))
            ->method('setLink')
            ->with('test', $this->isInstanceOf(LinkObject::class))
            ->willReturn(function(string $name, LinkObject $link) {
                $this->assertSame('http://test.com', $link->getReference());
            });

        $hydrator = $this->createMock(DocumentHydrator::class);
        $handler  = new LinksExtension();

        $handler->hydrate($object, ['test' => 'http://test.com'], $hydrator);

        $handler->hydrate($object, ['test' => (object) [
            'href' => 'http://test.com'
        ]], $hydrator);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Exception\InvalidDocumentException
     */
    public function testNoLinksAware()
    {
        $hydrator = $this->createMock(DocumentHydrator::class);
        $handler  = new LinksExtension();

        $handler->hydrate(new \stdClass(), (object) ['test' => 12345], $hydrator);
    }
}