<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

/**
 * No data document
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class NoDataDocument extends AbstractDocument
{
    /**
     * NoDataDocument constructor.
     *
     * @param array $metadata
     */
    public function __construct(array $metadata = [])
    {
        $this->metadata = $metadata;
    }
}