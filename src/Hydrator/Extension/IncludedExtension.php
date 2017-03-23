<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\Behaviour\IncludedResourcesAwareInterface;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;

/**
 * "included" extension
 *
 * @see http://jsonapi.org/format/#document-compound-documents
 *
 * @package Mikemirten\Component\JsonApi\Hydrator\Extension
 */
class IncludedExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate($object, $source, DocumentHydrator $hydrator)
    {
        if (! $object instanceof IncludedResourcesAwareInterface) {
            throw new InvalidDocumentException(sprintf(
                'Given instance of "%s" does not implements "%s"',
                get_class($object),
                IncludedResourcesAwareInterface::class
            ));
        }

        foreach ($source as $item)
        {
            $resource = $hydrator->hydrateResource($item);

            $object->addIncludedResource($resource);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(): array
    {
        return ['included'];
    }
}