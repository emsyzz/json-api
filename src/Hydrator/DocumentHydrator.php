<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Hydrator;

use Mikemirten\Component\JsonApi\Document\AbstractDocument;
use Mikemirten\Component\JsonApi\Document\NoDataDocument;
use Mikemirten\Component\JsonApi\Document\ResourceCollectionDocument;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Document\SingleResourceDocument;
use Mikemirten\Component\JsonApi\Hydrator\Extension\ExtensionInterface;

/**
 * Json API Document hydrator
 * Supports base set of members, others supposed to be supported by extensions (handlers) registered in the hydrator.
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * @package Mikemirten\Component\JsonApi\Hydrator
 */
class DocumentHydrator
{
    /**
     * Section handlers
     *
     * @var ExtensionInterface[]
     */
    protected $extensions = [];

    /**
     * Register extension
     *
     * @param ExtensionInterface $extension
     */
    public function registerExtension(ExtensionInterface $extension)
    {
        foreach ($extension->supports() as $section)
        {
            $this->extensions[$section] = $extension;
        }
    }

    /**
     * Hydrate source to a document
     *
     * @param  object $source
     * @return AbstractDocument
     */
    public function hydrate($source): AbstractDocument
    {
        if (! isset($source->data)) {
            return $this->processNoDataDocument($source);
        }

        if (is_object($source->data)) {
            return $this->processSingleResourceDocument($source);
        }

        if (is_array($source->data)) {
            return $this->processResourceCollectionDocument($source);
        }

        throw new InvalidDocumentException('If data is present and is not null it must be an object or an array');
    }

    /**
     * Hydrate object (part of document)
     *
     * @param  mixed $object Object for hydration
     * @param  mixed $source Source of data
     */
    public function hydrateObject($object, $source)
    {
        foreach ($source as $name => $section)
        {
            if (isset($this->extensions[$name])) {
                $this->extensions[$name]->hydrate($object, $section, $this);
            }
        }
    }

    /**
     * Process document contains no data
     *
     * @param  mixed $source
     * @return NoDataDocument
     */
    protected function processNoDataDocument($source): NoDataDocument
    {
        $document = new NoDataDocument();

        $this->hydrateObject($document, $source);

        return $document;
    }

    /**
     * Process document with single resource
     *
     * @param  mixed $source
     * @return SingleResourceDocument
     */
    protected function processSingleResourceDocument($source): SingleResourceDocument
    {
        $resource = $this->createResource($source->data);
        $document = new SingleResourceDocument($resource);

        $this->hydrateObject($document, $source);

        return $document;
    }

    /**
     * Process document with collection of resources
     *
     * @param  mixed $source
     * @return ResourceCollectionDocument
     */
    protected function processResourceCollectionDocument($source): ResourceCollectionDocument
    {
        $document = new ResourceCollectionDocument();

        foreach ($source->data as $resourceSrc)
        {
            $document->addResource($this->createResource($resourceSrc));
        }

        $this->hydrateObject($document, $source);

        return $document;
    }

    /**
     * Create resource
     *
     * @param  mixed $source
     * @return ResourceObject
     */
    protected function createResource($source): ResourceObject
    {
        if (! isset($source->id)) {
            throw new InvalidDocumentException('Resource contains no ID');
        }

        if (! isset($source->type)) {
            throw new InvalidDocumentException('Resource contains no type');
        }

        $attributes = empty($source->attributes) ? [] : get_object_vars($source->attributes);

        $resource = new ResourceObject($source->id, $source->type, $attributes);

        $this->hydrateObject($resource, $source);

        return $resource;
    }
}