<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler;

/**
 * DateTime handler
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler
 */
class DateTimeHandler implements DataTypeHandlerInterface
{
    const PARAMETER_FORMAT = 0;

    /**
     * {@inheritdoc}
     */
    public function toResource($value, array $parameters)
    {
        if ($value === null) {
            return;
        }

        if (! $value instanceof \DateTimeInterface) {
            $value = new \DateTimeImmutable((string) $value);
        }

        if (isset($parameters[self::PARAMETER_FORMAT])) {
            return $value->format($parameters[self::PARAMETER_FORMAT]);
        }

        return $value->format(DATE_RFC3339);
    }

    /**
     * {@inheritdoc}
     */
    public function fromResource($value, array $parameters)
    {
        if ($value === null) {
            return;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value;
        }

        return new \DateTimeImmutable((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(): array
    {
        return ['datetime'];
    }
}