<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

use Mikemirten\Component\JsonApi\Document\LinkObject;

/**
 * Links-container behaviour
 *
 * @see http://jsonapi.org/format/#document-links
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
trait LinksContainer
{
    /**
     * Links
     *
     * @var LinkObject[]
     */
    protected $links = [];

    /**
     * Set link
     *
     * @param string     $name
     * @param LinkObject $link
     */
    public function setLink(string $name, LinkObject $link)
    {
        $this->links[$name] = $link;
    }

    /**
     * Has link
     *
     * @param  string $name
     * @return bool
     */
    public function hasLink(string $name): bool
    {
        return isset($this->links[$name]);
    }

    /**
     * Get link
     *
     * @param  string $name
     * @return LinkObject
     */
    public function getLink(string $name): LinkObject
    {
        return $this->links[$name];
    }

    /**
     * Get all links
     *
     * @return LinkObject[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }
}