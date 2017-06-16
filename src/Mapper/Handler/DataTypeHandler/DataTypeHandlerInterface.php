<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler;

/**
 * Interface of data-type handler
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler
 */
interface DataTypeHandlerInterface
{
    /**
     * Map value to resource
     *
     * @param  $value
     * @param  array $parameters
     * @return mixed
     */
    public function toResource($value, array $parameters);

    /**
     * Map value from resource
     *
     * @param  $value
     * @param  array $parameters
     * @return mixed
     */
    public function fromResource($value, array $parameters);

    /**
     * Get a list of supported types
     *
     * ["type1", "type2", "typeN"]
     *
     * @return array
     */
    public function supports(): array;
}