<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient\Adapter;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Exception\BadResponseException as GuzzleResponseException;
use Mikemirten\Component\JsonApi\HttpClient\Exception\RequestException;
use Mikemirten\Component\JsonApi\HttpClient\Exception\ResponseException;
use Mikemirten\Component\JsonApi\HttpClient\HttpClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Guzzle HTTP Client adapter
 *
 * @package Mikemirten\Component\JsonApi\HttpClient
 */
class GuzzleAdapter implements HttpClientInterface
{
    /**
     * @var GuzzleClientInterface
     */
    protected $client;

    /**
     * GuzzleAdapter constructor.
     *
     * @param GuzzleClientInterface $client
     */
    public function __construct(GuzzleClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->client->send($request);
        }
        catch (GuzzleRequestException $exception) {
            throw $this->createException($exception);
        }
    }

    /**
     * Create HTTP-Client RequestException by a Guzzle exception
     *
     * @param  GuzzleRequestException $exception
     * @return RequestException
     */
    protected function createException(GuzzleRequestException $exception): RequestException
    {
        if ($exception instanceof GuzzleResponseException) {
            return new ResponseException(
                $exception->getRequest(),
                $exception->getResponse(),
                $exception
            );
        }

        throw new RequestException(
            $exception->getRequest(),
            $exception
        );
    }
}