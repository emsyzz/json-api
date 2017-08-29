<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler;

/**
 * Handler of PHP-native types
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler
 */
class NativeTypesHandler implements DataTypeHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function toResource($value, string $type, array $parameters)
    {
        return $this->process($value, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function fromResource($value, string $type, array $parameters)
    {
        return $this->process($value, $type);
    }

    /**
     * Process native type
     *
     * @param  mixed  $value
     * @param  string $type
     * @return mixed
     */
    protected function process($value, string $type)
    {
        if ($type === 'integer') {
            return (int) $value;
        }

        if ($type === 'float') {
            return (float) $value;
        }

        if ($type === 'boolean') {
            return (bool) $value;
        }

        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(): array
    {
        return ['integer', 'float', 'string', 'boolean'];
    }
}