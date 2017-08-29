<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseException
 *
 * @package Mikemirten\Component\JsonApi\HttpClient\Exception
 */
class ResponseException extends RequestException
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * ResponseException constructor.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param \Throwable        $previous
     */
    public function __construct(RequestInterface $request, ResponseInterface $response, \Throwable $previous)
    {
        parent::__construct($request, $previous);

        $this->response = $response;
    }

    /**
     * Get response caused the exception
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}