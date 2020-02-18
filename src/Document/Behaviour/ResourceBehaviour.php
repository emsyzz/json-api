<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Behaviour;

/**
 * Class ResourceBehaviour
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 *
 * @package Mikemirten\Component\JsonApi\Document\Behaviour
 */
trait ResourceBehaviour
{
    /**
     * ID of resource
     *
     * @var string|null
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
     * @return string|null
     */
    public function getId(): ?string
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

    /**
     * Cast resource to an array
     *
     * @return array
     */
    protected function resourceToArray(): array
    {
        $array = [
            'id' => $this->getId(),
            'type' => $this->getType(),
        ];

        if ($array['id'] === null) {
            unset($array['id']);
        }

        return $array;
    }
}