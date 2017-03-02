<?php
declare(strict_types = 1);

namespace Mikemirten\JsonApi\Component\Document;

/**
 * No data document
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * @package Mikemirten\JsonApi\Component\Document
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