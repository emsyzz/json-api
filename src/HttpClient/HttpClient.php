<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient;

use GuzzleHttp\Psr7\Stream;
use Mikemirten\Component\JsonApi\Document\AbstractDocument;
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
    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var DocumentHydrator
     */
    protected $hydrator;

    /**
     * HttpClient constructor.
     *
     * @param HttpClientInterface $client
     * @param DocumentHydrator    $hydrator
     */
    public function __construct(HttpClientInterface $client, DocumentHydrator $hydrator)
    {
        $this->client   = $client;
        $this->hydrator = $hydrator;
    }

    /**
     * {@inheritdoc}
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        if ($request instanceof JsonApiRequest) {
            $document = $request->getDocument();
            $stream   = $this->documentToStream($document);
            $request  = $request->withBody($stream);
        }

        $response = $this->client->request($request);

        if (in_array('application/vnd.api+json', $response->getHeader('Content-Type'), true)) {
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