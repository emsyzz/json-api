<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Document\IdentifierCollectionRelationship;
use Mikemirten\Component\JsonApi\Document\ResourceIdentifierObject;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Document\SingleIdentifierRelationship;
use Mikemirten\Component\JsonApi\Mapper\Definition\Relationship as RelationshipDefinition;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;

/**
 * Handler of relationships
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
class RelationshipHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function toResource($object, ResourceObject $resource, MappingContext $context)
    {
        $definitions = $context->getDefinition()->getRelationships();

        foreach ($definitions as $definition)
        {
            $relationship = $definition->isCollection()
                ? $this->createIdentifierCollectionRelationship($object, $definition)
                : $this->createSingleIdentifierRelationship($object, $definition);

            $resource->setRelationship($definition->getName(), $relationship);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fromResource($object, ResourceObject $resource, MappingContext $context)
    {
        // Do nothing
    }

    /**
     * Create relationship contains a single resource-identifier
     *
     * @param  mixed                  $object
     * @param  RelationshipDefinition $definition
     * @return ResourceIdentifierObject
     */
    protected function createSingleIdentifierRelationship($object, RelationshipDefinition $definition): SingleIdentifierRelationship
    {
        $relatedObject = $object->{$definition->getGetter()}();

        $idGetter     = $definition->getIdentifierGetter();
        $identifier   = (string) $relatedObject->$idGetter();
        $resourceType = $definition->getResourceType();

        $resource = new ResourceIdentifierObject($identifier, $resourceType);

        return new SingleIdentifierRelationship($resource);
    }

    /**
     * Create relationship contains a collection of resource-identifiers
     *
     * @param  mixed                  $object
     * @param  RelationshipDefinition $definition
     * @return IdentifierCollectionRelationship
     */
    protected function createIdentifierCollectionRelationship($object, RelationshipDefinition $definition): IdentifierCollectionRelationship
    {
        $idGetter     = $definition->getIdentifierGetter();
        $resourceType     = $definition->getResourceType();

        $relationship = new IdentifierCollectionRelationship();
        $collection   = $object->{$definition->getGetter()}();

        foreach ($collection as $relatedObject)
        {
            $identifier = (string) $relatedObject->$idGetter();
            $resource   = new ResourceIdentifierObject($identifier, $resourceType);

            $relationship->addIdentifier($resource);
        }

        return $relationship;
    }
}