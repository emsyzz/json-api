<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\IdentifierHandler;
use Mikemirten\Component\JsonApi\Mapper\MappingContext;

/**
 * Interface of identifier handler
 * An implementation suppose to be able to get/set an identifier to/from object
 *
 * @package Mikemirten\Component\JsonApi\ObjectTransformer\IdentifierHandler
 */
interface IdentifierHandlerInterface
{
    /**
     * Get identifier from object
     *
     * @param  mixed          $object
     * @param  MappingContext $context
     * @return string
     */
    public function getIdentifier($object, MappingContext $context): string;

    /**
     * Set identifier to object
     *
     * @param mixed          $object
     * @param string         $identifier
     * @param MappingContext $context
     */
    public function setIdentifier($object, string $identifier, MappingContext $context);
}