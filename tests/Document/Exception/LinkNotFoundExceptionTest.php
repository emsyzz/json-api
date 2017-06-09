<?php

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group   document
 * @group   exception
 * @package Document\Exception
 */
class LinkNotFoundExceptionTest extends TestCase
{
    public function test()
    {
        $container = $this->createMock(LinksAwareInterface::class);
        $exception = new LinkNotFoundException($container, 'test_link');

        $this->assertSame($container, $exception->getContainer());
        $this->assertSame('test_link', $exception->getName());
    }
}