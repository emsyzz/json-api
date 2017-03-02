<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\LinkObject;
use Mikemirten\Component\JsonApi\Exception\InvalidDocumentException;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;

/**
 * "links" object extension
 *
 * @see http://jsonapi.org/format/#document-links
 *
 * @package Mikemirten\Component\JsonApi\Hydrator\SectionHandler
 */
class LinksExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate($object, $source, DocumentHydrator $hydrator)
    {
        if (! $object instanceof LinksAwareInterface) {
            throw new InvalidDocumentException(sprintf(
                'Given instance of "%s" does not implements "%s"',
                get_class($object),
                LinksAwareInterface::class
            ));
        }

        foreach ($source as $name => $content)
        {
            $link = $this->createLink($content, $hydrator);

            $object->setLink($name, $link);
        }
    }

    /**
     * Create link
     *
     * @param  mixed            $source
     * @param  DocumentHydrator $hydrator
     * @return LinkObject
     */
    protected function createLink($source, DocumentHydrator $hydrator): LinkObject
    {
        if (is_string($source)) {
            return new LinkObject($source);
        }

        if (! isset($source->href)) {
            throw new InvalidDocumentException('Link must be a string or an object contains "href" attribute.');
        }

        $link = new LinkObject($source->href);

        $hydrator->hydrateObject($link, $source);

        return $link;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(): array
    {
        return ['links'];
    }
}