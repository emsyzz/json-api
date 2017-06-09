<?php

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\RelationshipsAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group   document
 * @group   exception
 * @package Document\Exception
 */
class RelationshipNotFoundExceptionTest extends TestCase
{
    public function test()
    {
        $container = $this->createMock(RelationshipsAwareInterface::class);
        $exception = new RelationshipNotFoundException($container, 'test_rel');

        $this->assertSame($container, $exception->getContainer());
        $this->assertSame('test_rel', $exception->getName());
    }
}