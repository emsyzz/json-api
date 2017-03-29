<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler;

use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\DeserializationContext;
use Mikemirten\Component\JsonApi\Document\ResourceObject;

/**
 * Attributes handler
 *
 * This handler is an integration with JMS Serializer library.
 * It using JMS Array Transformer ability for attributes mapping.
 *
 * @see http://jmsyst.com/libs/serializer
 * @see https://github.com/schmittjoh/serializer
 *
 * @package Mikemirten\Component\JsonApi\ObjectTransformer\Handler
 */
class JmsArrayTransformerHandler implements HandlerInterface
{
    /**
     * JMS Array Transformer
     *
     * @var ArrayTransformerInterface
     */
    protected $transformer;

    /**
     * JmsArrayTransformerHandler constructor.
     *
     * @param ArrayTransformerInterface $arrayTransformer
     */
    public function __construct(ArrayTransformerInterface $arrayTransformer)
    {
        $this->transformer = $arrayTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function toResource($object, ResourceObject $resource)
    {
        $data = $this->transformer->toArray($object);

        foreach ($data as $name => $value)
        {
            $resource->setAttribute($name, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fromResource($object, ResourceObject $resource)
    {
        $data = $resource->getAttributes();

        $context = new DeserializationContext();
        $context->setAttribute('target', $object);

        $this->transformer->fromArray($data, get_class($object), $context);
    }
}