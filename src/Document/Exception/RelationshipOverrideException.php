<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\Behaviour\RelationshipsAwareInterface;

/**
 * Class RelationshipOverrideException
 *
 * @package Mikemirten\Component\JsonApi\Exception
 */
class RelationshipOverrideException extends DocumentException
{
    /**
     * RelationshipOverrideException constructor.
     *
     * @param RelationshipsAwareInterface $container
     * @param string                      $name
     * @param \Exception | null           $previous
     */
    public function __construct(RelationshipsAwareInterface $container, string $name, \Exception $previous = null)
    {
        $info = method_exists($container, '__toString')
            ? (string) $container
            : get_class($container);

        $message = sprintf('Relationship "%s" already exists inside of [%s]. To set new one, the old one must be removed.', $name, $info);

        parent::__construct($message, 0, $previous);
    }
}