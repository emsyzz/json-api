<?php
declare(strict_types = 1);

namespace Mikemirten\JsonApi\Component\Document\Behaviour;

/**
 * Class ResourceBehaviour
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 *
 * @package Mikemirten\JsonApi\Component\Document\Behaviour
 */
trait ResourceBehaviour
{
    /**
     * ID of resource
     *
     * @var string
     */
    protected $id;

    /**
     * Type of resource
     *
     * @var string
     */
    protected $type;

    /**
     * Get ID of resource
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get type of resource
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}