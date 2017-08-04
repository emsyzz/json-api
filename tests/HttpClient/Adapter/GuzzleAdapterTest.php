<?php

namespace Mikemirten\Component\JsonApi\HttpClient\Adapter;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @group http-client
 */
class GuzzleAdapterTest extends TestCase
{
    public function testRequest()
    {
        $request  = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $guzzle = $this->createMock('GuzzleHttp\ClientInterface');

        $guzzle->expects($this->once())
            ->method('send')
            ->with($request)
            ->willReturn($response);

        $adapter = new GuzzleAdapter($guzzle);

        $this->assertSame($response, $adapter->request($request));
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\HttpClient\Exception\RequestException
     */
    public function testRequestException()
    {
        $request = $this->createMock(RequestInterface::class);
        $guzzle  = $this->createMock('GuzzleHttp\ClientInterface');

        $exception = $this->createMock(RequestException::class);

        $exception->method('getRequest')
            ->willReturn($request);

        $guzzle->expects($this->once())
            ->method('send')
            ->with($request)
            ->willThrowException($exception);

        $adapter = new GuzzleAdapter($guzzle);
        $adapter->request($request);
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\HttpClient\Exception\ResponseException
     */
    public function testResponseException()
    {
        $request  = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $guzzle   = $this->createMock('GuzzleHttp\ClientInterface');

        $exception = $this->createMock(BadResponseException::class);

        $exception->method('getRequest')
            ->willReturn($request);

        $exception->method('getResponse')
            ->willReturn($response);

        $guzzle->expects($this->once())
            ->method('send')
            ->with($request)
            ->willThrowException($exception);

        $adapter = new GuzzleAdapter($guzzle);
        $adapter->request($request);
    }
}