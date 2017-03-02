<?php
declare(strict_types = 1);

namespace Mikemirten\JsonApi\Component;

use Mikemirten\JsonApi\Component\Document\AbstractDocument;
use Mikemirten\JsonApi\Component\Document\LinkObject;
use Mikemirten\JsonApi\Component\Document\NoDataDocument;
use Mikemirten\JsonApi\Component\Document\ResourceCollectionDocument;
use Mikemirten\JsonApi\Component\Document\ResourceObject;
use Mikemirten\JsonApi\Component\Document\SingleResourceDocument;
use Mikemirten\JsonApi\Component\Exception\InvalidDocumentException;

/**
 * Factory of documents
 *
 * @package Mikemirten\JsonApi\Component
 */
class DocumentFactory
{
    /**
     * Create document
     *
     * @param  object $documentSrc
     * @return AbstractDocument
     */
    public function createDocument($documentSrc): AbstractDocument
    {
        if (! isset($documentSrc->data)) {
            return $this->processNoDataDocument($documentSrc);
        }

        if (is_object($documentSrc->data)) {
            return $this->processSingleResourceDocument($documentSrc);
        }

        if (! is_array($documentSrc->data)) {
            return $this->processResourceCollectionDocument($documentSrc);
        }

        throw new InvalidDocumentException('If data is present and is not null it must be an object or an array');
    }

    /**
     * Process document contains no data
     *
     * @param  $src
     * @return NoDataDocument
     */
    protected function processNoDataDocument($src): NoDataDocument
    {
        $document = new NoDataDocument($src->meta ?? []);

        if (isset($src->links)) {
            foreach ($src->links as $name => $content)
            {
                $document->setLink($name, $this->createLink($content));
            }
        }

        return $document;
    }

    /**
     * Process document with single resource
     *
     * @param  object $src
     * @return SingleResourceDocument
     */
    protected function processSingleResourceDocument($src): SingleResourceDocument
    {
        $resource = $this->createResource($src->data);
        $document = new SingleResourceDocument($resource, $src->meta ?? []);

        if (isset($src->links)) {
            foreach ($src->links as $name => $content)
            {
                $document->setLink($name, $this->createLink($content));
            }
        }

        return $document;
    }

    /**
     * Process document with collection of resources
     *
     * @param  $src
     * @return ResourceCollectionDocument
     */
    protected function processResourceCollectionDocument($src): ResourceCollectionDocument
    {
        $document = new ResourceCollectionDocument($src->meta ?? []);

        foreach ($src->data as $resourceSrc)
        {
            $document->addResource($this->createResource($resourceSrc));
        }

        if (isset($src->links)) {
            foreach ($src->links as $name => $content)
            {
                $document->setLink($name, $this->createLink($content));
            }
        }

        return $document;
    }

    /**
     * Create resource
     *
     * @param  object $resourceSrc
     * @return ResourceObject
     */
    public function createResource($resourceSrc): ResourceObject
    {
        if (! isset($resourceSrc->id)) {
            throw new InvalidDocumentException('Resource contains no ID');
        }

        if (! isset($resourceSrc->type)) {
            throw new InvalidDocumentException('Resource contains no type');
        }

        $resource = new ResourceObject(
            $resourceSrc->id,
            $resourceSrc->type,
            $resourceSrc->attributes ?? [],
            $resourceSrc->meta ?? []
        );

        if (isset($resourceSrc->links)) {
            foreach ($resourceSrc->links as $name => $content)
            {
                $resource->setLink($name, $this->createLink($content));
            }
        }

        return $resource;
    }

    /**
     * Create link
     *
     * @param  object $linkSrc
     * @return LinkObject
     */
    public function createLink($linkSrc): LinkObject
    {
        if (is_string($linkSrc)) {
            return new LinkObject($linkSrc);
        }

        if (! isset($linkSrc->href)) {
            throw new InvalidDocumentException('Link must be a string or an object contains "href" attribute.');
        }

        return new LinkObject($linkSrc->href, $linkSrc->meta ?? []);
    }
}