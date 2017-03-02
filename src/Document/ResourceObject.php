<?php
declare(strict_types = 1);

namespace Mikemirten\JsonApi\Component\Document;

use Mikemirten\JsonApi\Component\Document\Behaviour\AttributesContainer;
use Mikemirten\JsonApi\Component\Document\Behaviour\LinksContainer;
use Mikemirten\JsonApi\Component\Document\Behaviour\MetadataContainer;
use Mikemirten\JsonApi\Component\Document\Behaviour\ResourceBehaviour;

/**
 * Resource Object
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 *
 * @package Mikemirten\JsonApi\Component\Document
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