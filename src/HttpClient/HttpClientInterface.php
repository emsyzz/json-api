<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient;

use Mikemirten\Component\JsonApi\HttpClient\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface of a HttpClient
 *
 * @package Mikemirten\Component\JsonApi\HttpClient
 */
interface HttpClientInterface
{
    /**
     * Send HTTP request
     *
     * @param  RequestInterface $request
     * @return ResponseInterface
     * @throws RequestException
     */
    public function request(RequestInterface $request): ResponseInterface;
}