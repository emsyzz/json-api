<?php

namespace Mikemirten\Component\JsonApi\HttpClient;

use Mikemirten\Component\JsonApi\Document\AbstractDocument;
use Mikemirten\Component\JsonApi\HttpClient\Exception\ResponseException;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @group http-client
 */
class HttpClientTest extends TestCase
{
    public function testOptions()
    {
        $hydrator = $this->createMock(DocumentHydrator::class);
        $adapter  = $this->createMock(HttpClientInterface::class);

        $client = new HttpClient($adapter, $hydrator, [
            'returnBadResponse' => true
        ]);

        $this->assertTrue($client->isReturnBadResponse());
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\HttpClient\Exception\InvalidOptionException
     */
    public function testInvalidOption()
    {
        $hydrator = $this->createMock(DocumentHydrator::class);
        $adapter  = $this->createMock(HttpClientInterface::class);

        new HttpClient($adapter, $hydrator, [
            'invalidOption' => 123
        ]);
    }

    public function testRegularRequest()
    {
        $request        = $this->createMock(RequestInterface::class);
        $responseOrigin = $this->createResponse();

        $regularClient = $this->createMock(HttpClientInterface::class);

        $regularClient->expects($this->once())
            ->method('request')
            ->with($request)
            ->willReturn($responseOrigin);

        $hydrator = $this->createMock(DocumentHydrator::class);

        $hydrator->expects($this->never())
            ->method('hydrate');

        $jsonApiClient = new HttpClient($regularClient, $hydrator);

        $response = $jsonApiClient->request($request);

        $this->assertSame($responseOrigin, $response);
    }

    public function testJsonApiRequest()
    {
        $request = $this->createRequest(
            '{"data":"test"}',
            ['data' => 'test']
        );

        $responseOrigin = $this->createResponse();
        $regularClient  = $this->createMock(HttpClientInterface::class);

        $regularClient->expects($this->once())
            ->method('request')
            ->with($request)
            ->willReturn($responseOrigin);

        $hydrator = $this->createMock(DocumentHydrator::class);

        $hydrator->expects($this->never())
            ->method('hydrate');

        $jsonApiClient = new HttpClient($regularClient, $hydrator);

        $response = $jsonApiClient->request($request);

        $this->assertSame($responseOrigin, $response);
    }

    public function testJsonApiResponse()
    {
        $request        = $this->createMock(RequestInterface::class);
        $responseOrigin = $this->createResponse('application/vnd.api+json', '{"data":"test"}');

        $regularClient = $this->createMock(HttpClientInterface::class);

        $regularClient->expects($this->once())
            ->method('request')
            ->with($request)
            ->willReturn($responseOrigin);

        $hydrator = $this->createMock(DocumentHydrator::class);

        $hydrator->expects($this->once())
            ->method('hydrate')
            ->with((object) ['data' => 'test'])
            ->willReturn($this->createMock(AbstractDocument::class));

        $jsonApiClient = new HttpClient($regularClient, $hydrator);

        $response = $jsonApiClient->request($request);

        $this->assertInstanceOf(JsonApiResponse::class, $response);
        $this->assertInstanceOf(AbstractDocument::class, $response->getDocument());
    }

    /**
     * @expectedException \Mikemirten\Component\JsonApi\HttpClient\Exception\ResponseException
     */
    public function testResponseException()
    {
        $request   = $this->createMock(RequestInterface::class);
        $response  = $this->createResponse();
        $exception = $this->createMock(ResponseException::class);
        $hydrator  = $this->createMock(DocumentHydrator::class);

        $exception->method('getResponse')
            ->willReturn($response);

        $regularClient = $this->createMock(HttpClientInterface::class);

        $regularClient->expects($this->once())
            ->method('request')
            ->willThrowException($exception);

        $jsonApiClient = new HttpClient($regularClient, $hydrator);
        $jsonApiClient->request($request);
    }

    public function testBadResponse()
    {
        $request   = $this->createMock(RequestInterface::class);
        $response  = $this->createResponse();
        $exception = $this->createMock(ResponseException::class);
        $hydrator  = $this->createMock(DocumentHydrator::class);

        $exception->method('getResponse')
            ->willReturn($response);

        $regularClient = $this->createMock(HttpClientInterface::class);

        $regularClient->expects($this->once())
            ->method('request')
            ->willThrowException($exception);

        $jsonApiClient = new HttpClient($regularClient, $hydrator, [
            'returnBadResponse' => true
        ]);

        $result = $jsonApiClient->request($request);

        $this->assertSame($result, $response);
    }

    /**
     * Create mock of request
     *
     * @param  string $expectedBody
     * @param  array  $arrayDocument
     * @return RequestInterface
     */
    protected function createRequest(string $expectedBody, array $arrayDocument): RequestInterface
    {
        $request = $this->createMock(JsonApiRequest::class);

        $request->expects($this->once())
            ->method('withBody')
            ->with($this->isInstanceOf(StreamInterface::class))
            ->willReturnCallback(function(StreamInterface $stream) use($request, $expectedBody) {
                $this->assertSame(
                    $expectedBody,
                    $stream->getContents()
                );

                return $request;
            });


        $document = $this->createMock(AbstractDocument::class);

        $document->method('toArray')
            ->willReturn($arrayDocument);

        $request->expects($this->once())
            ->method('getDocument')
            ->willReturn($document);

        return $request;
    }

    /**
     * Create mock of response
     *
     * @param  string        $contentType
     * @param  string | null $content
     * @return ResponseInterface
     */
    protected function createResponse(string $contentType = 'text/html', string $content = null): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);

        $response->expects($this->once())
            ->method('getHeader')
            ->with('Content-Type')
            ->willReturn([$contentType]);

        $response->method('getHeaders')
            ->willReturn([[$contentType]]);

        $response->method('getStatusCode')
            ->willReturn(200);

        if ($content !== null) {
            $stream = $this->createMock(StreamInterface::class);

            $stream->method('getContents')
                ->willReturn($content);

            $response->expects($this->once())
                ->method('getBody')
                ->willReturn($stream);
        }

        return $response;
    }
}