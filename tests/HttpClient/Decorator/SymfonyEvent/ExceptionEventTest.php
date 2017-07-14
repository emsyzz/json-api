<?php

namespace Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @group   http-client
 * @package Mikemirten\Component\JsonApi\HttpClient\Decorator
 */
class ExceptionEventTest extends TestCase
{
    public function testRequest()
    {
        $request   = $this->createMock(RequestInterface::class);
        $exception = $this->createMock(\Exception::class);

        $event = new ExceptionEvent($request, $exception);

        $this->assertSame($request, $event->getRequest());
    }

    public function testResponse()
    {
        $request   = $this->createMock(RequestInterface::class);
        $response  = $this->createMock(ResponseInterface::class);
        $exception = $this->createMock(\Exception::class);

        $event = new ExceptionEvent($request, $exception);

        $this->assertFalse($event->hasResponse());

        $event->setResponse($response);

        $this->assertTrue($event->hasResponse());
        $this->assertSame($response, $event->getResponse());
    }

    public function testException()
    {
        $request   = $this->createMock(RequestInterface::class);
        $exception = $this->createMock(\Exception::class);

        $event = new ExceptionEvent($request, $exception);

        $this->assertSame($exception, $event->getException());
    }

    public function testResponseEventControl()
    {
        $request   = $this->createMock(RequestInterface::class);
        $exception = $this->createMock(\Exception::class);

        $event = new ExceptionEvent($request, $exception);

        $this->assertTrue($event->isResponseEventEnabled());

        $event->disableResponseEvent();

        $this->assertFalse($event->hasResponse());
    }
}