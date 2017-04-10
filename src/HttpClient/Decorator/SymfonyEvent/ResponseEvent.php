<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\EventDispatcher\Event;

class ResponseEvent extends Event
{
    /**
     * Response
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * ResponseEvent constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Set response
     *
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Get response
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}