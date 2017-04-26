<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\Behaviour;

use Mikemirten\Component\JsonApi\Mapper\Definition\Link;

/**
 * An implementation of links-container behaviour
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\Behaviour
 */
trait LinksContainer
{
    /**
     * Collection of links
     *
     * @var Link[]
     */
    protected $links = [];

    /**
     * Set link
     *
     * @param Link $link
     */
    public function addLink(Link $link)
    {
        $name = $link->getName();

        if (isset($this->links[$name])) {
            throw new \LogicException(sprintf('A link name by "%s" is already exists.', $name));
        }

        $this->links[$name] = $link;
    }

    /**
     * Get links
     * [name => Link]
     *
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * Merge links
     *
     * @param LinksAwareInterface $container
     */
    protected function mergeLinks(LinksAwareInterface $container)
    {
        foreach ($container->getLinks() as $name => $link)
        {
            if (isset($this->links[$name])) {
                $this->links[$name]->merge($link);
                continue;
            }

            $this->links[$name] = $link;
        }
    }
}