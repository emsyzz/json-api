<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\AbstractRelationship;
use Mikemirten\Component\JsonApi\Document\Behaviour\RelationshipsAwareInterface;
use Mikemirten\Component\JsonApi\Document\IdentifierCollectionRelationship;
use Mikemirten\Component\JsonApi\Document\NoDataRelationship;
use Mikemirten\Component\JsonApi\Document\ResourceIdentifierObject;
use Mikemirten\Component\JsonApi\Document\SingleIdentifierRelationship;
use Mikemirten\Component\JsonApi\Exception\InvalidDocumentException;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;

/**
 * "relationships" object extension
 *
 * @package Mikemirten\Component\JsonApi\Hydrator\Extension
 */
class RelationshipExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate($object, $source, DocumentHydrator $hydrator)
    {
        if (! $object instanceof RelationshipsAwareInterface) {
            throw new InvalidDocumentException(sprintf(
                'Given instance of "%s" does not implements "%s"',
                get_class($object),
                RelationshipsAwareInterface::class
            ));
        }

        foreach ($source as $name => $content)
        {
            $relationship = $this->createRelationship($content, $hydrator);

            $object->setRelationship($name, $relationship);
        }
    }

    /**
     * Create relationship
     *
     * @param  mixed            $source
     * @param  DocumentHydrator $hydrator
     * @return AbstractRelationship
     */
    public function createRelationship($source, DocumentHydrator $hydrator): AbstractRelationship
    {
        if (! isset($source->data)) {
            return $this->processNoDataRelationship($source, $hydrator);
        }

        if (is_object($source->data)) {
            return $this->processSingleIdentifierRelationship($source, $hydrator);
        }

        if (is_array($source->data)) {
            return $this->processIdentifierCollectionRelationship($source, $hydrator);
        }

        throw new InvalidDocumentException('If data is present and is not null it must be an object or an array');
    }

    /**
     * Process relationship contains no data
     *
     * @param  mixed            $source
     * @param  DocumentHydrator $hydrator
     * @return NoDataRelationship
     */
    protected function processNoDataRelationship($source, DocumentHydrator $hydrator): NoDataRelationship
    {
        $relationship = new NoDataRelationship();

        $hydrator->hydrateObject($relationship, $source);

        return $relationship;
    }

    /**
     * Process relationship with single resource identifier
     *
     * @param  mixed            $source
     * @param  DocumentHydrator $hydrator
     * @return SingleIdentifierRelationship
     */
    protected function processSingleIdentifierRelationship($source, DocumentHydrator $hydrator): SingleIdentifierRelationship
    {
        $identifier   = $this->createResourceIdentifier($source->data, $hydrator);
        $relationship = new SingleIdentifierRelationship($identifier);

        $hydrator->hydrateObject($relationship, $source);

        return $relationship;
    }

    /**
     * Process relationship with collection of resource identifiers
     *
     * @param  mixed            $source
     * @param  DocumentHydrator $hydrator
     * @return IdentifierCollectionRelationship
     */
    protected function processIdentifierCollectionRelationship($source, DocumentHydrator $hydrator): IdentifierCollectionRelationship
    {
        $relationship = new IdentifierCollectionRelationship();

        foreach ($source->data as $resourceSrc)
        {
            $relationship->addIdentifier($this->createResourceIdentifier($resourceSrc, $hydrator));
        }

        $hydrator->hydrateObject($relationship, $source);

        return $relationship;
    }

    /**
     * Create resource
     *
     * @param  mixed            $source
     * @param  DocumentHydrator $hydrator
     * @return ResourceIdentifierObject
     */
    protected function createResourceIdentifier($source, DocumentHydrator $hydrator): ResourceIdentifierObject
    {
        if (! isset($source->id)) {
            throw new InvalidDocumentException('Resource identifier contains no ID');
        }

        if (! isset($source->type)) {
            throw new InvalidDocumentException('Resource identifier contains no type');
        }

        $identifier = new ResourceIdentifierObject($source->id, $source->type);

        $hydrator->hydrateObject($identifier, $source);

        return $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(): array
    {
        return ['relationships'];
    }
}