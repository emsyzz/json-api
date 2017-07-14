<?php

namespace Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @group   http-client
 * @package Mikemirten\Component\JsonApi\HttpClient\Decorator
 */
class ResponseEventTest extends TestCase
{
    public function testGetResponse()
    {
        $response = $this->createMock(ResponseInterface::class);
        $event    = new ResponseEvent($response);

        $this->assertSame($response, $event->getResponse());
    }

    /**
     * @depends testGetResponse
     */
    public function testSetResponse()
    {
        $response = $this->createMock(ResponseInterface::class);
        $event    = new ResponseEvent($response);

        $response2 = $this->createMock(ResponseInterface::class);
        $event->setResponse($response2);

        $this->assertSame($response2, $event->getResponse());
    }
}