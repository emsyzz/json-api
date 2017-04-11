<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Exception;

use Mikemirten\Component\JsonApi\Document\Behaviour\RelationshipsAwareInterface;

/**
 * Class RelationshipNotFoundException
 *
 * @package Mikemirten\Component\JsonApi\Exception
 */
class RelationshipNotFoundException extends JsonApiException
{
    /**
     * RelationshipNotFoundException constructor.
     *
     * @param RelationshipsAwareInterface $container
     * @param string                      $name
     * @param \Exception | null           $previous
     */
    public function __construct(RelationshipsAwareInterface $container, string $name, \Exception $previous = null)
    {
        $message = sprintf('Relationship "%s" not found inside of [%s].', $name, $container);

        parent::__construct($message, 0, $previous);
    }
}