<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\Behaviour\RelationshipsAwareInterface;

/**
 * Class RelationshipNotFoundException
 *
 * @package Mikemirten\Component\JsonApi\Exception
 */
class RelationshipNotFoundException extends DocumentException
{
    /**
     * Name of not found relationship
     *
     * @var string
     */
    protected $name;

    /**
     * Container inside of which a relationship has not been found
     *
     * @var RelationshipsAwareInterface
     */
    protected $container;

    /**
     * RelationshipNotFoundException constructor.
     *
     * @param RelationshipsAwareInterface $container
     * @param string                      $name
     * @param \Exception | null           $previous
     */
    public function __construct(RelationshipsAwareInterface $container, string $name, \Exception $previous = null)
    {
        $this->name      = $name;
        $this->container = $container;

        $info = method_exists($container, '__toString')
            ? (string) $container
            : get_class($container);

        $message = sprintf('Relationship "%s" not found inside of [%s].', $name, $info);

        parent::__construct($message, 0, $previous);
    }

    /**
     * Get name of not found relationship
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get container inside of which a relationship has not been found
     *
     * @return RelationshipsAwareInterface
     */
    public function getContainer(): RelationshipsAwareInterface
    {
        return $this->container;
    }
}