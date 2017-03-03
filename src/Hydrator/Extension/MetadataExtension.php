<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use Mikemirten\Component\JsonApi\Exception\InvalidDocumentException;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;

/**
 * "data" object extension
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 *
 * @package Mikemirten\Component\JsonApi\Hydrator\SectionHandler
 */
class MetadataExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate($object, $source, DocumentHydrator $hydrator)
    {
        if (! $object instanceof MetadataAwareInterface) {
            throw new InvalidDocumentException(sprintf(
                'Given instance of "%s" does not implements "%s"',
                get_class($object),
                MetadataAwareInterface::class
            ));
        }

        foreach ($source as $name => $value)
        {
            $object->setMetadataAttribute($name, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(): array
    {
        return ['meta'];
    }
}