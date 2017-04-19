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
        LinksAwareDocumentInterface   $document,
        array $scope = []
    ) {
        foreach ($definition->getLinks() as $linkDefinition)
        {
            $repoName = $linkDefinition->getRepositoryName();
            $linkName = $linkDefinition->getLinkName();

            $parameters = $this->resolveParameters($object, $linkDefinition, $scope);

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
     * @param  array          $scope
     * @return array
     */
    protected function resolveParameters($object, LinkDefinition $definition, array $scope): array
    {
        $resolved = [];

        foreach ($definition->getParameters() as $name => $value)
        {
            if (! preg_match('~@(?:(?<namespace>[a-z0-9_\.]+)\:)?(?<name>[a-z0-9_\.]+)~i', $value, $matches)) {
                $resolved[$name] = $value;
                continue;
            }

            if (empty($matches['namespace'])) {
                $resolved[$name] = $this->accessor->getValue($object, $matches['name']);
                continue;
            }

            if (! isset($scope[$matches['namespace']])) {
                throw new \LogicException(sprintf('Object "%s" not found in the scope.', $matches['namespace']));
            }

            $resolved[$name] = $this->accessor->getValue(
                $scope[$matches['namespace']],
                $matches['name']
            );
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