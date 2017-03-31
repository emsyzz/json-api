<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler\IdentifierHandler;

use Mikemirten\Component\JsonApi\Mapper\MappingContext;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\IdentifierHandler
 */
class RigidIdentifierHandlerTest extends TestCase
{
    public function testGetIdentifier()
    {
        $object = new class
        {
            public function getId()
            {
                return '123';
            }
        };

        $handler = new RigidIdentifierHandler('getId');
        $context = $this->createMock(MappingContext::class);

        $this->assertSame('123', $handler->getIdentifier($object, $context));
    }

    public function testSetIdentifier()
    {
        $object = new class
        {
            public $id;

            public function setId($id)
            {
                $this->id = $id;
            }
        };

        $handler = new RigidIdentifierHandler('getId', 'setId');
        $context = $this->createMock(MappingContext::class);

        $handler->setIdentifier($object, '123', $context);

        $this->assertSame('123', $object->id);
    }
}