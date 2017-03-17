<?php

namespace Mikemirten\Component\JsonApi\HttpClient;

use Mikemirten\Component\JsonApi\Document\AbstractDocument;
use PHPUnit\Framework\TestCase;

/**
 * @group http-client
 */
class JsonApiResponseTest extends TestCase
{
    public function testDocument()
    {
        $document = $this->createMock(AbstractDocument::class);
        $response = new JsonApiResponse(200, [], $document);

        $this->assertSame($document, $response->getDocument());
    }
}