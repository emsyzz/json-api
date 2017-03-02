<?php
declare(strict_types = 1);

namespace Mikemirten\JsonApi\Component\Document;

use Mikemirten\JsonApi\Component\Document\Behaviour\MetadataContainer;
use Mikemirten\JsonApi\Component\Document\Behaviour\ResourceBehaviour;

/**
 * Resource Identifier Object
 *
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 *
 * @package Mikemirten\JsonApi\Component\Document
 */
class ResourceIdentifierObject
{
    use ResourceBehaviour;
    use MetadataContainer;

    /**
     * ResourceObject constructor.
     *
     * @param string $id
     * @param string $type
     * @param array  $metadata
     */
    public function __construct(string $id, string $type, array $metadata = [])
    {
        $this->id       = $id;
        $this->type     = $type;
        $this->metadata = $metadata;
    }
}