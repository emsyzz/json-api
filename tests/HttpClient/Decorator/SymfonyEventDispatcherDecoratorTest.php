<?php

namespace Mikemirten\Component\JsonApi\HttpClient\Decorator;

use Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent\ExceptionEvent;
use Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent\RequestEvent;
use Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent\ResponseEvent;
use Mikemirten\Component\JsonApi\HttpClient\Exception\RequestException;
use Mikemirten\Component\JsonApi\HttpClient\HttpClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @group   http-client
 * @package Mikemirten\Component\JsonApi\HttpClient\Decorator
 */
class SymfonyEventDispatcherDecoratorTest extends TestCase
{
    public function testDispatching()
    {
        $request  = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $client = $this->createMock(HttpClientInterface::class);

        $client->expects($this->once())
            ->method('request')
            ->with($request)
            ->willReturn($response);

        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $dispatcher->expects($this->at(0))
            ->method('dispatch')
            ->with(
                'event.request',
                $this->isInstanceOf(RequestEvent::class)
            )
            ->willReturnCallback(
                function(string $name, RequestEvent $event) use($request)
                {
                    $this->assertSame($request, $event->getRequest());
                }
            );

        $dispatcher->expects($this->at(1))
            ->method('dispatch')
            ->with(
                'event.response',
                $this->isInstanceOf(ResponseEvent::class)
            )
            ->willReturnCallback(
                function(string $name, ResponseEvent $event) use($response)
                {
                    $this->assertSame($response, $event->getResponse());
                }
            );

        $decorator = new SymfonyEventDispatcherDecorator(
            $client,
            $dispatcher,
            'event.request',
            'event.response',
            'event.exception'
        );

        $result = $decorator->request($request);
        $this->assertSame($response, $result);
    }

    public function testDispatchingException()
    {
        $request   = $this->createMock(RequestInterface::class);
        $response  = $this->createMock(ResponseInterface::class);
        $exception = $this->createMock(\Exception::class);

        $client = $this->createMock(HttpClientInterface::class);

        $client->expects($this->once())
            ->method('request')
            ->with($request)
            ->willThrowException($exception);

        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $dispatcher->expects($this->at(1))
            ->method('dispatch')
            ->with(
                'event.exception',
                $this->isInstanceOf(ExceptionEvent::class)
            )
            ->willReturnCallback(
                function(string $name, ExceptionEvent $event) use($request, $response, $exception)
                {
                    $this->assertSame($request, $event->getRequest());
                    $this->assertSame($exception, $event->getException());

                    $event->setResponse($response);
                }
            );

        $decorator = new SymfonyEventDispatcherDecorator(
            $client,
            $dispatcher,
            'event.request',
            'event.response',
            'event.exception'
        );

        $result = $decorator->request($request);
        $this->assertSame($response, $result);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\HttpClient\Exception\RequestException
     */
    public function testDispatchingExceptionThow()
    {
        $request    = $this->createMock(RequestInterface::class);
        $exception  = $this->createMock(\Exception::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $client     = $this->createMock(HttpClientInterface::class);

        $client->method('request')
            ->willThrowException($exception);

        $decorator = new SymfonyEventDispatcherDecorator(
            $client,
            $dispatcher,
            'event.request',
            'event.response',
            'event.exception'
        );

        $decorator->request($request);
    }

    public function testDispatchingClientExceptionThrow()
    {
        $request    = $this->createMock(RequestInterface::class);
        $exception  = $this->createMock(RequestException::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $client     = $this->createMock(HttpClientInterface::class);

        $client->method('request')
            ->willThrowException($exception);

        $decorator = new SymfonyEventDispatcherDecorator(
            $client,
            $dispatcher,
            'event.request',
            'event.response',
            'event.exception'
        );

        try {
            $decorator->request($request);
        } catch (\Throwable $thrown) {
            $this->assertSame($thrown, $exception);
            return;
        }

        $this->fail('An exception has not been caught');
    }
}