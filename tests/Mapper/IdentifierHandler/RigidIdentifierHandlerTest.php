<?php

namespace Mikemirten\Component\JsonApi\Mapper\IdentifierHandler;

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

        $this->assertSame('123', $handler->getIdentifier($object));
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
        $handler->setIdentifier($object, '123');

        $this->assertSame('123', $object->id);
    }
}