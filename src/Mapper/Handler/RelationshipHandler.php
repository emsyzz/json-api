<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Document\IdentifierCollectionRelationship;
use Mikemirten\Component\JsonApi\Document\ResourceIdentifierObject;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Document\SingleIdentifierRelationship;
use Mikemirten\Component\JsonApi\Mapper\Definition\Relationship as RelationshipDefinition;
use Mikemirten\Component\JsonApi\Mapper\Handler\LinkHandler\LinkHandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;

/**
 * Handler of relationships
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
class RelationshipHandler implements HandlerInterface
{
    /**
     * Links' handler
     *
     * @var LinkHandlerInterface
     */
    protected $linkHandler;

    /**
     * RelationshipHandler constructor.
     *
     * @param LinkHandlerInterface $linkHandler
     */
    public function __construct(LinkHandlerInterface $linkHandler)
    {
        $this->linkHandler = $linkHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function toResource($object, ResourceObject $resource, MappingContext $context)
    {
        $definitions = $context->getDefinition()->getRelationships();

        foreach ($definitions as $definition)
        {
            $relationship = $definition->isCollection()
                ? $this->createIdentifierCollectionRelationship($object, $definition, $context)
                : $this->createSingleIdentifierRelationship($object, $definition, $context);

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
     * @return SingleIdentifierRelationship
     */
    protected function createSingleIdentifierRelationship($object, RelationshipDefinition $definition, MappingContext $context): SingleIdentifierRelationship
    {
        $relatedObject = $object->{$definition->getGetter()}();

        $identifier   = $this->resolveIdentifier($relatedObject, $definition, $context);
        $resourceType = $this->resolveType($relatedObject, $definition, $context);

        $resource     = new ResourceIdentifierObject($identifier, $resourceType);
        $relationship = new SingleIdentifierRelationship($resource);

        $this->linkHandler->handleLinks($object, $definition, $relationship);

        return $relationship;
    }

    /**
     * Create relationship contains a collection of resource-identifiers
     *
     * @param  mixed                  $object
     * @param  RelationshipDefinition $definition
     * @return IdentifierCollectionRelationship
     */
    protected function createIdentifierCollectionRelationship($object, RelationshipDefinition $definition, MappingContext $context): IdentifierCollectionRelationship
    {
        $relationship = new IdentifierCollectionRelationship();
        $collection   = $object->{$definition->getGetter()}();

        foreach ($collection as $relatedObject)
        {
            $identifier   = $this->resolveIdentifier($relatedObject, $definition, $context);
            $resourceType = $this->resolveType($relatedObject, $definition, $context);
            $resource     = new ResourceIdentifierObject($identifier, $resourceType);

            $relationship->addIdentifier($resource);
        }

        $this->linkHandler->handleLinks($object, $definition, $relationship);

        return $relationship;
    }

    /**
     * Resolve ID of resource
     *
     * @param  mixed                  $object
     * @param  RelationshipDefinition $definition
     * @param  MappingContext         $context
     * @return string
     */
    protected function resolveIdentifier($object, RelationshipDefinition $definition, MappingContext $context): string
    {
        if ($definition->hasIdentifierGetter()) {
            $method = $definition->getIdentifierGetter();

            return (string) $object->$method();
        }

        return $context->getIdentifierHandler()->getIdentifier($object, $context);
    }

    /**
     * Resolve type of resource
     *
     * @param  mixed                  $object
     * @param  RelationshipDefinition $definition
     * @param  MappingContext         $context
     * @return string
     */
    protected function resolveType($object, RelationshipDefinition $definition, MappingContext $context): string
    {
        if ($definition->hasResourceType()) {
            return $definition->getResourceType();
        }

        return $context->getTypeHandler()->getType($object, $context);
    }
}