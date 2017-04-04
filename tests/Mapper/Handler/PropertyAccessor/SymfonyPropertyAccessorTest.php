<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler\PropertyAccessor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface as SymfonyPropertyAccessorInterface;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\PropertyAccessor
 */
class SymfonyPropertyAccessorTest extends TestCase
{
    public function testGetValue()
    {
        $object = new \stdClass();

        $symfonyAccessor = $this->createMock(SymfonyPropertyAccessorInterface::class);

        $symfonyAccessor->expects($this->once())
            ->method('getValue')
            ->with($object, 'test')
            ->willReturn('123');

        $accessor = new SymfonyPropertyAccessor($symfonyAccessor);
        $result   = $accessor->getValue($object, 'test');

        $this->assertSame('123', $result);
    }
}