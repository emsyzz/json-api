<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\IdentifierCollectionAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\IdentifierCollectionContainer;

/**
 * Identifier Collection Document
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class IdentifierCollectionDocument extends AbstractDocument implements IdentifierCollectionAwareInterface
{
    use IdentifierCollectionContainer;

    /**
     * IdentifierCollectionDocument constructor.
     *
     * @param array $metadata
     */
    public function __construct(array $metadata = [])
    {
        $this->metadata = $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        $data['data'] = $this->identifiersToArray();

        return $data;
    }

    /**
     * Cast to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'Document contains a collection of resource-identifiers';
    }
}