<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\AttributesContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\ResourceBehaviour;

/**
 * Resource Object
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class ResourceObject
{
    use ResourceBehaviour;
    use AttributesContainer;
    use MetadataContainer;
    use LinksContainer;

    /**
     * ResourceObject constructor.
     *
     * @param string $id
     * @param string $type
     * @param array  $attributes
     * @param array  $metadata
     */
    public function __construct(string $id, string $type, array $attributes = [], array $metadata = [])
    {
        $this->id         = $id;
        $this->type       = $type;
        $this->attributes = $attributes;
        $this->metadata   = $metadata;
    }
}