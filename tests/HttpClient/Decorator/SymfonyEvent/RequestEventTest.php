<?php

namespace Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @group   http-client
 * @package Mikemirten\Component\JsonApi\HttpClient\Decorator
 */
class RequestEventTest extends TestCase
{
    public function testGetRequest()
    {
        $request = $this->createMock(RequestInterface::class);
        $event   = new RequestEvent($request);

        $this->assertSame($request, $event->getRequest());
    }

    /**
     * @depends testGetRequest
     */
    public function testSetRequest()
    {
        $request = $this->createMock(RequestInterface::class);
        $event   = new RequestEvent($request);

        $request2 = $this->createMock(RequestInterface::class);
        $event->setRequest($request2);

        $this->assertSame($request2, $event->getRequest());
    }
}