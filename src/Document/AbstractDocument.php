<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\ErrorsAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\ErrorsContainer;
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
abstract class AbstractDocument implements MetadataAwareInterface, LinksAwareInterface, ErrorsAwareInterface
{
    use MetadataContainer;
    use LinksContainer;
    use ErrorsContainer;

    /**
     * Cast to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->hasMetadata()) {
            $data['meta'] = $this->getMetadata();
        }

        if ($this->hasLinks()) {
            $data['links'] = $this->linksToArray();
        }

        if ($this->hasErrors()) {
            $data['errors'] = $this->errorsToArray();
        }

        return $data;
    }
}