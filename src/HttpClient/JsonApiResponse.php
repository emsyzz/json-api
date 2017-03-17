<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient;

use GuzzleHttp\Psr7\Response;
use Mikemirten\Component\JsonApi\Document\AbstractDocument;

/**
 * JsonApi extension of Response
 *
 * @package Mikemirten\Component\JsonApi\HttpClient
 */
class JsonApiResponse extends Response
{
    /**
     * @var AbstractDocument
     */
    protected $document;

    /**
     * JsonApiResponse constructor.
     *
     * @param int              $status
     * @param array            $headers
     * @param AbstractDocument $document
     */
    public function __construct($status, array $headers, AbstractDocument $document)
    {
        $this->document = $document;

        parent::__construct($status, $headers);
    }

    /**
     * Get JsonApi-document
     *
     * @return AbstractDocument
     */
    public function getDocument(): AbstractDocument
    {
        return $this->document;
    }
}