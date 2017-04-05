<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\Behaviour;

use Mikemirten\Component\JsonApi\Mapper\Definition\Link;

/**
 * Interface of a class implements links-container behaviour
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\Behaviour
 */
interface LinksAwareInterface
{
    /**
     * Set link
     *
     * @param Link $link
     */
    public function addLink(Link $link);

    /**
     * Get links
     * [name => Link]
     *
     * @return Link[]
     */
    public function getLinks(): array;
}