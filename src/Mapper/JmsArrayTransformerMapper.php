<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper;

use JMS\Serializer\ArrayTransformerInterface as ArrayTransformer;
use JMS\Serializer\DeserializationContext;
use Mikemirten\Component\JsonApi\Mapper\IdentifierHandler\IdentifierHandlerInterface as IdentifierHandler;
use Mikemirten\Component\JsonApi\Mapper\TypeHandler\TypeHandlerInterface as TypeHandler;

/**
 * Mapper based on JMS Array Transformer (a part of JMS Serializer)
 *
 * @package Mikemirten\Component\JsonApi\ObjectTransformer
 */
class JmsArrayTransformerMapper extends AbstractObjectMapper
{
    /**
     * JMS Array Transformer
     *
     * @var ArrayTransformer
     */
    protected $arrayTransformer;

    /**
     * JmsArrayTransformerMapper constructor.
     *
     * @param IdentifierHandler $identifierHandler
     * @param TypeHandler       $typeHandler
     * @param ArrayTransformer  $arrayTransformer
     */
    public function __construct(IdentifierHandler $identifierHandler, TypeHandler $typeHandler, ArrayTransformer $arrayTransformer)
    {
        parent::__construct($identifierHandler, $typeHandler);

        $this->arrayTransformer = $arrayTransformer;
    }

    /**
     * {@inheritdoc}
     */
    protected function toArray($object): array
    {
        return $this->arrayTransformer->toArray($object);
    }

    /**
     * {@inheritdoc}
     */
    protected function fromArray($object, array $data)
    {
        $context = new DeserializationContext();
        $context->setAttribute('target', $object);

        $this->arrayTransformer->fromArray($data, get_class($object), $context);
    }
}