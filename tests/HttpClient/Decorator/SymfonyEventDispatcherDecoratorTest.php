<?php

namespace Mikemirten\Component\JsonApi\HttpClient\Decorator;

use Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent\RequestEvent;
use Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent\ResponseEvent;
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
                SymfonyEventDispatcherDecorator::EVENT_REQUEST,
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
                SymfonyEventDispatcherDecorator::EVENT_RESPONSE,
                $this->isInstanceOf(ResponseEvent::class)
            )
            ->willReturnCallback(
                function(string $name, ResponseEvent $event) use($response)
                {
                    $this->assertSame($response, $event->getResponse());
                }
            );

        $decorator = new SymfonyEventDispatcherDecorator($client, $dispatcher);
        $result    = $decorator->request($request);

        $this->assertSame($response, $result);
    }
}