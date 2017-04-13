<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\ResourceBehaviour;

/**
 * Resource Identifier Object
 *
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class ResourceIdentifierObject implements MetadataAwareInterface
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

    /**
     * Cast to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = $this->resourceToArray();

        if ($this->hasMetadata()) {
            $data['meta'] = $this->getMetadata();
        }

        return $data;
    }

    /**
     * Cast to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('Identifier of resource with ID: "%s" and type: "%s"', $this->id, $this->type);
    }
}