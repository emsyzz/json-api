<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\AttributesContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\RelationshipsAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\RelationshipsContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\ResourceBehaviour;

/**
 * Resource Object
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class ResourceObject implements MetadataAwareInterface, LinksAwareInterface, RelationshipsAwareInterface
{
    use ResourceBehaviour;
    use AttributesContainer;
    use MetadataContainer;
    use LinksContainer;
    use RelationshipsContainer;

    /**
     * ResourceObject constructor.
     *
     * @param string|null $id
     * @param string $type
     * @param array  $attributes
     * @param array  $metadata
     */
    public function __construct(?string $id, string $type, array $attributes = [], array $metadata = [])
    {
        $this->id         = $id;
        $this->type       = $type;
        $this->attributes = $attributes;
        $this->metadata   = $metadata;
    }

    /**
     * Cast to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'meta'          => $this->getMetadata(),
            'links'         => $this->linksToArray(),
            'attributes'    => $this->getAttributes(),
            'relationships' => $this->relationshipsToArray()
        ];

        return array_merge(
            $this->resourceToArray(),
            array_filter($data, 'count')
        );
    }

    /**
     * Cast to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        if ($this->id !== null) {
            return sprintf(
                'Resource-object of type "%s" identified by "%s"',
                $this->type,
                $this->id
            );
        }

        return sprintf(
            'Resource-object of type "%s" without identifier',
            $this->type
        );
    }
}