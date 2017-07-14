<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * On exception event
 *
 * @package Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent
 */
class ExceptionEvent extends Event
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var \Throwable
     */
    protected $exception;

    /**
     * If the exception will be resolved to a response (through setResponse()),
     * response-event will be fired until explicitly disabled by this property.
     *
     * @var bool
     */
    protected $responseEventEnabled = true;

    /**
     * ExceptionEvent constructor.
     *
     * @param RequestInterface $request
     * @param \Throwable       $exception
     */
    public function __construct(RequestInterface $request, \Throwable $exception)
    {
        $this->request   = $request;
        $this->exception = $exception;
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

    /**
     * Get exception
     *
     * @return \Throwable
     */
    public function getException(): \Throwable
    {
        return $this->exception;
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
     * Has a response set by previous listeners ?
     *
     * @return bool
     */
    public function hasResponse(): bool
    {
        return $this->response !== null;
    }

    /**
     * Get response
     *
     * @return ResponseInterface
     * @throws \OutOfBoundsException
     */
    public function getResponse(): ResponseInterface
    {
        if ($this->response === null) {
            throw new \OutOfBoundsException('Response has never been set.');
        }

        return $this->response;
    }

    /**
     * Disable dispatching of response-event.
     * Enabled by default.
     */
    public function disableResponseEvent()
    {
        $this->responseEventEnabled = false;
    }

    /**
     * Enable dispatching of response-event.
     * Can be useful to reenable after has been disabled by another event-listener.
     */
    public function enableResponseEvent()
    {
        $this->responseEventEnabled = false;
    }

    /**
     * Is response event enabled to dispatch in the case if the exception
     * will be resolved to a response (through setResponse()) ?
     *
     * @return bool
     */
    public function isResponseEventEnabled(): bool
    {
        return $this->responseEventEnabled;
    }
}