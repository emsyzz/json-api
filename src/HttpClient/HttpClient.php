<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient;

use GuzzleHttp\Psr7\Stream;
use Mikemirten\Component\JsonApi\Document\AbstractDocument;
use Mikemirten\Component\JsonApi\HttpClient\Exception\HttpClientException;
use Mikemirten\Component\JsonApi\HttpClient\Exception\InvalidOptionException;
use Mikemirten\Component\JsonApi\HttpClient\Exception\ResponseException;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * HttpClient
 * Supports JsonApi requests & responses
 *
 * @package Mikemirten\Component\JsonApi\HttpClient
 */
class HttpClient implements HttpClientInterface
{
    const CONTENT_TYPE_JSON_API = 'application/vnd.api+json';

    /**
     * Options possible to configure
     *
     * @var array
     */
    static protected $possibleOptions = ['returnBadResponse'];

    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var DocumentHydrator
     */
    protected $hydrator;

    /**
     * Return bad response instead of throwing an exception
     *
     * @var bool
     */
    protected $returnBadResponse = false;

    /**
     * HttpClient constructor.
     *
     * @param HttpClientInterface $client
     * @param DocumentHydrator    $hydrator
     * @param array               $options
     */
    public function __construct(HttpClientInterface $client, DocumentHydrator $hydrator, array $options = [])
    {
        $this->client   = $client;
        $this->hydrator = $hydrator;

        foreach ($options as $option => $value)
        {
            if (! in_array($option, static::$possibleOptions, true)) {
                throw new InvalidOptionException($option, static::$possibleOptions);
            }

            $this->$option = $value;
        }
    }

    /**
     * Is the client returns bad response instead of throwing an exception ?
     *
     * @return bool
     */
    public function isReturnBadResponse(): bool
    {
        return $this->returnBadResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        $request = $this->handleRequest($request);

        try {
            $response = $this->client->request($request);
        }
        catch (ResponseException $exception) {
            $responseRaw = $exception->getResponse();
            $response    = $this->handleResponse($responseRaw);

            if ($this->returnBadResponse) {
                return $response;
            }

            throw new ResponseException($request, $response, $exception);
        }

        return $this->handleResponse($response);
    }

    /**
     * Handle request
     *
     * @param  RequestInterface $request
     * @return RequestInterface
     */
    protected function handleRequest(RequestInterface $request): RequestInterface
    {
        if ($request instanceof JsonApiRequest) {
            $document = $request->getDocument();
            $stream   = $this->documentToStream($document);

            return $request->withBody($stream);
        }

        return $request;
    }

    /**
     * Handle response
     *
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    protected function handleResponse(ResponseInterface $response): ResponseInterface
    {
        $contentType = $response->getHeader('Content-Type');

        if (in_array(self::CONTENT_TYPE_JSON_API, $contentType, true)) {
            $stream = $response->getBody();

            return new JsonApiResponse(
                $response->getStatusCode(),
                $response->getHeaders(),
                $this->streamToDocument($stream)
            );
        }

        return $response;
    }

    /**
     * Create a JsonApi document by serialized data from stream
     *
     * @param  StreamInterface $stream
     * @return AbstractDocument
     */
    protected function streamToDocument(StreamInterface $stream): AbstractDocument
    {
        $content = $stream->getContents();
        $decoded = json_decode($content);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Decoding error: "' . json_last_error_msg() . '""');
        }

        return $this->hydrator->hydrate($decoded);
    }

    /**
     * Create a stream contains serialized JsonApi-document
     *
     * @param  AbstractDocument $document
     * @return StreamInterface
     */
    protected function documentToStream(AbstractDocument $document): StreamInterface
    {
        $encoded = json_encode($document->toArray());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Encoding error: "' . json_last_error_msg() . '""');
        }

        $stream = fopen('php://memory', 'r+');

        fwrite($stream, $encoded);
        fseek($stream, 0);

        return new Stream($stream);
    }
}