<?php

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\ResourceObject;
use PHPUnit\Framework\TestCase;

/**
 * @group   document
 * @group   exception
 * @package Document\Exception
 */
class AttributeNotFoundExceptionTest extends TestCase
{
    public function test()
    {
        $resource  = $this->createMock(ResourceObject::class);
        $exception = new AttributeNotFoundException($resource, 'test_attr');

        $this->assertSame($resource, $exception->getResource());
        $this->assertSame('test_attr', $exception->getName());
    }
}