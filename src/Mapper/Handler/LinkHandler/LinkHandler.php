<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\LinkHandler;

use Mikemirten\Component\JsonApi\Document\LinkObject;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\Definition\Link as LinkDefinition;
use Mikemirten\Component\JsonApi\Mapper\Handler\HandlerInterface;
use Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository\Link as LinkData;
use Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository\RepositoryProvider as LinkRepositoryProvider;
use Mikemirten\Component\JsonApi\Mapper\Handler\PropertyAccessor\PropertyAccessorInterface;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;
use Mikemirten\Component\JsonApi\Mapper\Definition\Behaviour\LinksAwareInterface as LinksAwareDefinitionInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface as LinksAwareDocumentInterface;

/**
 * An implementation of links handler based on repositories of links.
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\LinkHandler
 */
class LinkHandler implements HandlerInterface, LinkHandlerInterface
{
    /**
     * Provider of links' repositories
     *
     * @var LinkRepositoryProvider
     */
    protected $provider;

    /**
     * Property accessor
     *
     * @var PropertyAccessorInterface
     */
    protected $accessor;

    /**
     * LinkHandler constructor.
     *
     * @param LinkRepositoryProvider    $provider
     * @param PropertyAccessorInterface $accessor
     */
    public function __construct(LinkRepositoryProvider $provider, PropertyAccessorInterface $accessor)
    {
        $this->provider = $provider;
        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function toResource($object, ResourceObject $resource, MappingContext $context)
    {
        $this->handleLinks($object, $context->getDefinition(), $resource);
    }

    /**
     * {@inheritdoc}
     */
    public function handleLinks(
        $object,
        LinksAwareDefinitionInterface $definition,
        LinksAwareDocumentInterface   $document
    ) {
        foreach ($definition->getLinks() as $linkDefinition)
        {
            $repoName = $linkDefinition->getRepositoryName();
            $linkName = $linkDefinition->getLinkName();

            $parameters = $this->resolveParameters($object, $linkDefinition);

            $linkData = $this->provider
                ->getRepository($repoName)
                ->getLink($linkName, $parameters);

            $link = $this->createLink($linkDefinition, $linkData);

            $document->setLink($linkDefinition->getName(), $link);
        }
    }

    /**
     * Create link-object
     *
     * @param  LinkDefinition $definition
     * @param  LinkData       $data
     * @return LinkObject
     */
    protected function createLink(LinkDefinition $definition, LinkData $data): LinkObject
    {
        $reference = $data->getReference();

        $metadata = array_replace(
            $data->getMetadata(),
            $definition->getMetadata()
        );

        return new LinkObject($reference, $metadata);
    }

    /**
     * Resolve parameters
     *
     * @param  mixed          $object
     * @param  LinkDefinition $definition
     * @return array
     */
    protected function resolveParameters($object, LinkDefinition $definition): array
    {
        $resolved = [];

        foreach ($definition->getParameters() as $name => $value)
        {
            if (strpos($value, '@') === 0) {
                $resolved[$name] = $this->accessor->getValue($object, substr($value, 1));
                continue;
            }

            $resolved[$name] = $value;
        }

        return $resolved;
    }

    /**
     * {@inheritdoc}
     */
    public function fromResource($object, ResourceObject $resource, MappingContext $context)
    {
        // Do nothing
    }
}