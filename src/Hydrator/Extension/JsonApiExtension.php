<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\AbstractDocument;
use Mikemirten\Component\JsonApi\Document\JsonApiObject;
use Mikemirten\Component\JsonApi\Exception\InvalidDocumentException;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;

/**
 * "jsonapi" object extension
 *
 * @see http://jsonapi.org/format/#document-jsonapi-object
 *
 * @package Mikemirten\Component\JsonApi\Hydrator\Extension
 */
class JsonApiExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate($object, $source, DocumentHydrator $hydrator)
    {
        if (! $object instanceof AbstractDocument) {
            throw new InvalidDocumentException(sprintf(
                'Only top-level of document can contains "jsonapi"-object. Instance of "%s" given.',
                get_class($object)
            ));
        }

        $jsonApi = $this->createJsonApi($source, $hydrator);

        $object->setJsonApi($jsonApi);
    }

    /**
     * Create JsonAPI-object
     *
     * @param  $source
     * @param  DocumentHydrator $hydrator
     * @return JsonApiObject
     */
    protected function createJsonApi($source, DocumentHydrator $hydrator): JsonApiObject
    {
        $jsonApi = isset($source->version)
            ? new JsonApiObject($source->version)
            : new JsonApiObject();

        $hydrator->hydrateObject($jsonApi, $source);

        return $jsonApi;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(): array
    {
        return ['jsonapi'];
    }
}