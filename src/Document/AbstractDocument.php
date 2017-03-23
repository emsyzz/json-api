<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\ErrorsAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\ErrorsContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\IncludedResourcesAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\IncludedResourcesContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataContainer;

/**
 * Abstract Document
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * Represents base JsonAPI-document structure
 * Supposed to be extended by case-based documents
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
abstract class AbstractDocument implements
    MetadataAwareInterface,
    LinksAwareInterface,
    ErrorsAwareInterface,
    IncludedResourcesAwareInterface
{
    use MetadataContainer;
    use LinksContainer;
    use ErrorsContainer;
    use IncludedResourcesContainer;

    /**
     * JsonAPI-object
     *
     * @see http://jsonapi.org/format/#document-jsonapi-object
     *
     * @var JsonApiObject
     */
    protected $jsonApi;

    /**
     * Set JsonAPI-object
     *
     * @param JsonApiObject $jsonApi
     */
    public function setJsonApi(JsonApiObject $jsonApi)
    {
        $this->jsonApi = $jsonApi;
    }

    /**
     * Set JsonAPI-object
     *
     * @return JsonApiObject
     */
    public function getJsonApi(): JsonApiObject
    {
        return $this->jsonApi;
    }

    /**
     * Cast to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'meta'     => $this->getMetadata(),
            'links'    => $this->linksToArray(),
            'errors'   => $this->errorsToArray(),
            'included' => $this->includedResourcesToArray()
        ];

        $data = array_filter($data, 'count');

        if ($this->jsonApi !== null) {
            $data['jsonapi'] = $this->jsonApi->toArray();
        }

        return $data;
    }
}