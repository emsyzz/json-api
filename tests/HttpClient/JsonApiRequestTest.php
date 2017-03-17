<?php

namespace Mikemirten\Component\JsonApi\HttpClient;

use Mikemirten\Component\JsonApi\Document\AbstractDocument;
use PHPUnit\Framework\TestCase;

/**
 * @group http-client
 */
class JsonApiRequestTest extends TestCase
{
    public function testDocument()
    {
        $document = $this->createMock(AbstractDocument::class);
        $request  = new JsonApiRequest('POST', 'http://domain.com', [], $document);

        $this->assertSame($document, $request->getDocument());
    }
}