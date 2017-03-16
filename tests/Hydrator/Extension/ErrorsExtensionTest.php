<?php

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\Behaviour\ErrorsAwareInterface;
use Mikemirten\Component\JsonApi\Document\ErrorObject;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;
use PHPUnit\Framework\TestCase;

/**
 * @group hydrator
 */
class ErrorsExtensionTest extends TestCase
{
    public function testSupports()
    {
        $extension = new ErrorsExtension();

        $this->assertSame(['errors'], $extension->supports());
    }

    public function testHydrate()
    {
        $object = $this->createMock(ErrorsAwareInterface::class);

        $object->expects($this->once())
            ->method('addError')
            ->with($this->isInstanceOf(ErrorObject::class))
            ->willReturnCallback(function(ErrorObject $error)
            {
                $this->assertSame('1', $error->getId());
                $this->assertSame('2', $error->getStatus());
                $this->assertSame('3', $error->getCode());
                $this->assertSame('4', $error->getTitle());
                $this->assertSame('5', $error->getDetail());
            });

        $extension = new ErrorsExtension();
        $hydrator  = $this->createMock(DocumentHydrator::class);

        $source = [(object) [
            'id'     => '1',
            'status' => '2',
            'code'   => '3',
            'title'  => '4',
            'detail' => '5'
        ]];

        $extension->hydrate($object, $source, $hydrator);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\Exception\InvalidDocumentException
     */
    public function testNoErrorsAware()
    {
        $hydrator = $this->createMock(DocumentHydrator::class);
        $extension  = new ErrorsExtension();

        $extension->hydrate(new \stdClass(), (object) ['test' => 12345], $hydrator);
    }
}