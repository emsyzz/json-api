<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient\Exception;

use Psr\Http\Message\RequestInterface;

/**
 * Class HttpRequestException
 *
 * @package Mikemirten\Component\JsonApi\HttpClient\Exception
 */
class RequestException extends HttpClientException
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * RequestException constructor.
     *
     * @param RequestInterface $request
     * @param \Exception       $previous
     */
    public function __construct(RequestInterface $request, \Exception $previous)
    {
        $this->request = $request;

        $message = sprintf(
            'An exception occurred while request to "%s" with message: "%s"',
            $request->getUri(),
            $previous->getMessage()
        );

        parent::__construct($message, 0, $previous);
    }

    /**
     * Get request
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}