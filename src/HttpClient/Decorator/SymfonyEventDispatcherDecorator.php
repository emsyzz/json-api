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
        $requestEvent = new RequestEvent($request);
        $this->dispatcher->dispatch($this->requestEvent, $requestEvent);

        try {
            $response = $this->client->request($requestEvent->getRequest());
        }
        catch (\Throwable $exception) {
            $exceptionEvent = new ExceptionEvent($request, $exception);
            $this->dispatcher->dispatch($this->exceptionEvent, $exceptionEvent);

            if (! $exceptionEvent->hasResponse()) {
                throw new RequestException($request, $exception);
            }

            $response = $exceptionEvent->getResponse();

            if (! $exceptionEvent->isResponseEventEnabled()) {
                return $response;
            }
        }

        $responseEvent = new ResponseEvent($response);
        $this->dispatcher->dispatch($this->responseEvent, $responseEvent);

        return $responseEvent->getResponse();
    }
}