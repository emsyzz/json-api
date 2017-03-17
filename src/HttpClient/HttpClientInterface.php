<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient;

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
     */
    public function request(RequestInterface $request): ResponseInterface;
}