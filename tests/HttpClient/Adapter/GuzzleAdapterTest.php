<?php

namespace Mikemirten\Component\JsonApi\HttpClient\Adapter;

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
}