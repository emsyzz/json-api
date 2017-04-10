<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient\Decorator;

use Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent\RequestEvent;
use Mikemirten\Component\JsonApi\HttpClient\Decorator\SymfonyEvent\ResponseEvent;
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
    const EVENT_REQUEST  = 'mrtn_json_api.http_client.request';
    const EVENT_RESPONSE = 'mrtn_json_api.http_client.response';

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
     * SymfonyEventDispatcherDecorator constructor.
     *
     * @param HttpClientInterface      $client
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(HttpClientInterface $client, EventDispatcherInterface $dispatcher)
    {
        $this->client     = $client;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        $requestEvent = new RequestEvent($request);
        $this->dispatcher->dispatch(self::EVENT_REQUEST, $requestEvent);

        $response = $this->client->request($requestEvent->getRequest());

        $responseEvent = new ResponseEvent($response);
        $this->dispatcher->dispatch(self::EVENT_RESPONSE, $responseEvent);

        return $responseEvent->getResponse();
    }
}