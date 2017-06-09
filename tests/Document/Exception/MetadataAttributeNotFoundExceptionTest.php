<?php

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group   document
 * @group   exception
 * @package Document\Exception
 */
class MetadataAttributeNotFoundExceptionTest extends TestCase
{
    public function test()
    {
        $container = $this->createMock(MetadataAwareInterface::class);
        $exception = new MetadataAttributeNotFoundException($container, 'test_attr');

        $this->assertSame($container, $exception->getContainer());
        $this->assertSame('test_attr', $exception->getName());
    }
}