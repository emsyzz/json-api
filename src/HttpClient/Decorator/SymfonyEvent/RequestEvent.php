<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent;

use Symfony\Component\EventDispatcher\Event;
use Psr\Http\Message\RequestInterface;

/**
 * Pre-request request event
 *
 * @package Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent
 */
class RequestEvent extends Event
{
    /**
     * Request
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * PreRequestEvent constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Set request
     *
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
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