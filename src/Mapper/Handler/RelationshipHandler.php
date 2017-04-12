<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Document\AbstractRelationship;
use Mikemirten\Component\JsonApi\Document\IdentifierCollectionRelationship;
use Mikemirten\Component\JsonApi\Document\NoDataRelationship;
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
            $relationship = $this->createRelationship($object, $definition, $context);
            $this->linkHandler->handleLinks($object, $definition, $relationship);

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
     * @param  MappingContext         $context
     * @return AbstractRelationship
     */
    protected function createRelationship($object, RelationshipDefinition $definition, MappingContext $context): AbstractRelationship
    {
        if (! $definition->isDataIncluded()) {
            return new NoDataRelationship();
        }

        return $definition->isCollection()
            ? $this->createIdentifierCollectionRelationship($object, $definition, $context)
            : $this->createSingleIdentifierRelationship($object, $definition, $context);
    }

    /**
     * Create relationship contains a single resource-identifier
     *
     * @param  mixed                  $object
     * @param  RelationshipDefinition $definition
     * @param  MappingContext         $context
     * @return AbstractRelationship ( NoDataRelationship | NoDataRelationship )
     */
    protected function createSingleIdentifierRelationship($object, RelationshipDefinition $definition, MappingContext $context): AbstractRelationship
    {
        $relatedObject = $object->{$definition->getGetter()}();

        if ($relatedObject === null) {
            return new NoDataRelationship();
        }

        $resource = $context->getMapper()->toResourceIdentifier($relatedObject);

        return new SingleIdentifierRelationship($resource);
    }

    /**
     * Create relationship contains a collection of resource-identifiers
     *
     * @param  mixed                  $object
     * @param  RelationshipDefinition $definition
     * @param  MappingContext         $context
     * @return IdentifierCollectionRelationship
     */
    protected function createIdentifierCollectionRelationship($object, RelationshipDefinition $definition, MappingContext $context): IdentifierCollectionRelationship
    {
        $relationship = new IdentifierCollectionRelationship();
        $collection   = $object->{$definition->getGetter()}();
        $dataLimit    = $definition->getDataLimit();

        if ($dataLimit > 0) {
            $collection = new \LimitIterator($collection, 0, $dataLimit);
        }

        $mapper = $context->getMapper();

        foreach ($collection as $relatedObject)
        {
            $resource = $mapper->toResourceIdentifier($relatedObject);

            $relationship->addIdentifier($resource);
        }

        return $relationship;
    }
}