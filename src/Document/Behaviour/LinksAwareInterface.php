<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

use Mikemirten\Component\JsonApi\Document\LinkObject;

/**
 * Interface of an object aware of links
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
interface LinksAwareInterface
{
    /**
     * Set link
     *
     * @param string     $name
     * @param LinkObject $link
     */
    public function setLink(string $name, LinkObject $link);

    /**
     * Has link
     *
     * @param  string $name
     * @return bool
     */
    public function hasLink(string $name): bool;

    /**
     * Get link
     *
     * @param  string $name
     * @return LinkObject
     */
    public function getLink(string $name): LinkObject;

    /**
     * Get all links
     *
     * @return LinkObject[]
     */
    public function getLinks(): array;
}