<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use Mikemirten\Component\JsonApi\Document\LinkObject;
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Mapper\Definition\Link as LinkDefinition;
use Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository\RepositoryProvider as LinkRepositoryProvider;
use Mikemirten\Component\JsonApi\Mapper\Handler\PropertyAccessor\PropertyAccessorInterface;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;

class LinkHandler implements HandlerInterface
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
        foreach ($context->getDefinition()->getLinks() as $linkDefinition)
        {
            $repoName = $linkDefinition->getRepositoryName();
            $linkName = $linkDefinition->getLinkName();

            $parameters = $this->resolveParameters($object, $linkDefinition);

            $linkData = $this->provider
                ->getRepository($repoName)
                ->getLink($linkName, $parameters);

            $link = new LinkObject(
                $linkData->getReference(),
                array_replace(
                    $linkData->getMetadata(),
                    $linkDefinition->getMetadata()
                )
            );

            $resource->setLink($linkDefinition->getName(), $link);
        }
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