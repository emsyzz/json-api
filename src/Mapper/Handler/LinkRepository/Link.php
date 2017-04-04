<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository;

/**
 * Link
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository
 */
class Link
{
    /**
     * Reference to a resource
     *
     * @var string
     */
    protected $reference;

    /**
     * Extra metadata
     *
     * @var array
     */
    protected $metadata;

    /**
     * Link constructor.
     *
     * @param string $reference
     * @param array  $metadata
     */
    public function __construct(string $reference, array $metadata = [])
    {
        $this->reference = $reference;
        $this->metadata  = $metadata;
    }

    /**
     * Get reference to a resource
     *
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Get extra metadata
     *
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
}