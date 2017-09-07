<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Link;

abstract class AbstractProcessor implements ConfigurationProcessorInterface
{
    /**
     * Pattern of "resource" parameter of link annotation
     */
    const RESOURCE_PATTERN = '~^(?<repository>[a-z_][a-z0-9_]*)\.(?<link>[a-z_][a-z0-9_]*)$~i';

    /**
     * Create link
     *
     * @param  string $name
     * @param  array  $data
     * @return Link
     */
    public function createLink(string $name, array $data): Link
    {
        if (! preg_match(self::RESOURCE_PATTERN, $data['resource'], $matches)) {
            throw new \LogicException(sprintf('Invalid resource definition: "%s"', $data['resource']));
        }

        $link = new Link(
            $name,
            $matches['repository'],
            $matches['link']
        );

        $link->setParameters($data['parameters']);
        $link->setMetadata($data['metadata']);

        return $link;
    }
}