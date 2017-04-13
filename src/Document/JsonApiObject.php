<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataContainer;

/**
 * Json API info container
 *
 * @see http://jsonapi.org/format/#document-jsonapi-object
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class JsonApiObject implements MetadataAwareInterface
{
    use MetadataContainer;

    /**
     * Version of standard
     *
     * @var string
     */
    protected $version;

    /**
     * JsonApiObject constructor.
     *
     * @param string $version
     * @param array  $metadata
     */
    public function __construct(string $version = '1.0', array $metadata = [])
    {
        $this->version  = $version;
        $this->metadata = $metadata;
    }

    /**
     * Set version of standard
     *
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    /**
     * Get version of standard
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Cast to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'version' => $this->getVersion()
        ];

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
        return sprintf('JsonAPI-object of version "%s"', $this->version);
    }
}