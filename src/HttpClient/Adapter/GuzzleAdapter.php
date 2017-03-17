<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient\Adapter;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
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
        return $this->client->send($request);
    }
}