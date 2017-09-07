<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor;

use Mikemirten\Component\JsonApi\Mapper\Definition\Link;

/**
 * Abstract processor
 * Contains shared methods
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition\ConfigurationProcessor
 */
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

    /**
     * Resolve getter of related object
     *
     * @param  \ReflectionClass $reflection
     * @param  string           $name
     * @return string
     */
    protected function resolveGetter(\ReflectionClass $reflection, string $name)
    {
        foreach (['get', 'is'] as $prefix)
        {
            $getter = $prefix . ucfirst($name);

            if ($reflection->hasMethod($getter) && $reflection->getMethod($getter)->isPublic()) {
                return $getter;
            }
        }

        throw new \LogicException(sprintf(
            'Getter-method for "%s" cannot be resolved automatically. ' .
            'Probably there is no get%2$s() or is%2$s() method or it is not public.',
            $name, ucfirst($name)
        ));
    }
}