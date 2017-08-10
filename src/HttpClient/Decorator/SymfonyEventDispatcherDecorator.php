<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient\Decorator;

use Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent\ExceptionEvent;
use Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent\RequestEvent;
use Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent\ResponseEvent;
use Mikemirten\Component\JsonApi\HttpClient\Exception\RequestException;
use Mikemirten\Component\JsonApi\HttpClient\HttpClientInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Events handling decorator based on the Symfony EventDispatcher component.
 *
 * @package Mikemirten\Component\JsonApi\HttpClient\Decorator
 */
class SymfonyEventDispatcherDecorator implements HttpClientInterface
{
    /**
     * HTTP Client
     *
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var string
     */
    protected $requestEvent;

    /**
     * @var string
     */
    protected $responseEvent;

    /**
     * @var string
     */
    protected $exceptionEvent;

    /**
     * SymfonyEventDispatcherDecorator constructor.
     *
     * @param HttpClientInterface      $client
     * @param EventDispatcherInterface $dispatcher
     *
     * @param string $requestEvent
     * @param string $responseEvent
     * @param string $exceptionEvent
     */
    public function __construct(
        HttpClientInterface      $client,
        EventDispatcherInterface $dispatcher,

        string $requestEvent,
        string $responseEvent,
        string $exceptionEvent
    ) {
        $this->client     = $client;
        $this->dispatcher = $dispatcher;

        $this->requestEvent   = $requestEvent;
        $this->responseEvent  = $responseEvent;
        $this->exceptionEvent = $exceptionEvent;
    }

    /**
     * {@inheritdoc}
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        $request = $this->dispatchOnRequest($request);

        try {
            $response = $this->client->request($request);
        }
        catch (\Throwable $exception) {
            $exceptionEvent = new ExceptionEvent($request, $exception);
            $this->dispatcher->dispatch($this->exceptionEvent, $exceptionEvent);

            if (! $exceptionEvent->hasResponse()) {
                throw $this->handleException($exception, $request);
            }

            $response = $exceptionEvent->getResponse();

            if (! $exceptionEvent->isResponseEventEnabled()) {
                return $response;
            }
        }

        return $this->dispatchOnResponse($response);
    }

    /**
     * Handler exception
     *
     * @param  \Throwable       $exception
     * @param  RequestInterface $request
     * @return RequestException
     */
    protected function handleException(\Throwable $exception, RequestInterface $request): RequestException
    {
        if ($exception instanceof RequestException) {
            return $exception;
        }

        return new RequestException($request, $exception);
    }

    /**
     * Dispatch on-request event
     *
     * @param  RequestInterface $request
     * @return RequestInterface
     */
    protected function dispatchOnRequest(RequestInterface $request): RequestInterface
    {
        $requestEvent = new RequestEvent($request);
        $this->dispatcher->dispatch($this->requestEvent, $requestEvent);

        return $requestEvent->getRequest();
    }

    /**
     * Dispatch on-response event
     *
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    protected function dispatchOnResponse(ResponseInterface $response): ResponseInterface
    {
        $responseEvent = new ResponseEvent($response);
        $this->dispatcher->dispatch($this->responseEvent, $responseEvent);

        return $responseEvent->getResponse();
    }
}