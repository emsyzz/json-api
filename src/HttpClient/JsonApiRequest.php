<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient;

use GuzzleHttp\Psr7\Request;
use Mikemirten\Component\JsonApi\Document\AbstractDocument;

/**
 * JsonApi extension of Request
 *
 * @package Mikemirten\Component\JsonApi\HttpClient
 */
class JsonApiRequest extends Request
{
    /**
     * @var AbstractDocument
     */
    protected $document;

    /**
     * JsonApiRequest constructor.
     *
     * @param string                $method
     * @param string | UriInterface $uri
     * @param array                 $headers
     * @param AbstractDocument      $body
     */
    public function __construct($method, $uri, array $headers = [], AbstractDocument $body)
    {
        $this->document = $body;

        if (! isset($headers['Content-Type'])) {
            $headers['Content-Type'] = ['application/vnd.api+json'];
        }

        parent::__construct($method, $uri, $headers);
    }

    /**
     * Get document
     *
     * @return AbstractDocument
     */
    public function getDocument(): AbstractDocument
    {
        return $this->document;
    }
}