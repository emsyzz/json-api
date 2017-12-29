<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler;

/**
 * DateInterval handler
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\DateIntervalHandler
 */
class DateIntervalHandler implements DataTypeHandlerInterface
{
    const PARAMETER_FORMAT = 0;

    /**
     * {@inheritdoc}
     */
    public function toResource($value, string $type, array $parameters)
    {
        if ($value === null) {
            return;
        }

        if (! $value instanceof \DateInterval) {
            $value = new \DateInterval((string) $value);
        }

        if (isset($parameters[self::PARAMETER_FORMAT])) {
            return $value->format($parameters[self::PARAMETER_FORMAT]);
        }

        $format = $this->resolveFormat($value);

        if ($format !== null) {
            return $value->format($format);
        }
    }

    /**
     * Resolve ISO_8601 duration format
     *
     * @param  \DateInterval $interval
     * @return string | null
     */
    protected function resolveFormat(\DateInterval $interval): ?string
    {
        $baseFormat = $this->resolveBaseFormat($interval);
        $timeFormat = $this->resolveTimeFormat($interval);

        if ($baseFormat === '') {
            if ($timeFormat === '') {
                return null;
            }

            return 'PT' . $timeFormat;
        }

        if ($timeFormat === '') {
            return 'P' . $baseFormat;
        }

        return 'P' . $baseFormat . 'T' . $timeFormat;
    }

    /**
     * Resolve base part of interval format
     *
     * @param  \DateInterval $interval
     * @return string
     */
    protected function resolveBaseFormat(\DateInterval $interval): string
    {
        $format = '';

        if ($interval->y > 0) {
            $format .= '%yY';
        }

        if ($interval->m > 0) {
            $format .= '%mM';
        }

        if ($interval->d > 0) {
            $format .= '%dD';
        }

        return $format;
    }

    /**
     * Resolve time part of interval format
     *
     * @param  \DateInterval $interval
     * @return string
     */
    protected function resolveTimeFormat(\DateInterval $interval): string
    {
        $format = '';

        if ($interval->h > 0) {
            $format .= '%hH';
        }

        if ($interval->i > 0) {
            $format .= '%iM';
        }

        if ($interval->s > 0) {
            $format .= '%sS';
        }

        return $format;
    }

    /**
     * {@inheritdoc}
     */
    public function fromResource($value, string $type, array $parameters)
    {
        if ($value === null) {
            return;
        }

        if ($value instanceof \DateInterval) {
            return $value;
        }

        return new \DateInterval((string) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(): array
    {
        return ['dateinterval'];
    }
}