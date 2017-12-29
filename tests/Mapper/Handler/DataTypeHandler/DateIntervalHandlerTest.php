<?php
declare(strict_types = 1);

namespace Mapper\Handler\DataTypeHandler;

use Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler\DateIntervalHandler;
use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Handler
 */
class DateIntervalHandlerTest extends TestCase
{
    /**
     * @dataProvider getToResourceData
     *
     * @param string $definition
     */
    public function testToResource(string $definition)
    {
        $handler  = new DateIntervalHandler();
        $interval = new \DateInterval($definition);

        $value = $handler->toResource($interval, 'dateinterval', []);

        $this->assertSame($definition, $value);
    }

    public function testToResourceCustomFormat()
    {
        $handler  = new DateIntervalHandler();
        $interval = new \DateInterval('P1Y');

        $value = $handler->toResource($interval, 'dateinterval', ['P%yY']);

        $this->assertSame('P1Y', $value);
    }

    public function testToResourceEmptyInterval()
    {
        $handler  = new DateIntervalHandler();
        $interval = new \DateInterval('P0Y');

        $value = $handler->toResource($interval, 'dateinterval', []);

        $this->assertNull($value);
    }

    public function testFromResource()
    {
        $handler = new DateIntervalHandler();
        $value   = $handler->fromResource('P1YT2H', 'dateinterval', []);

        $this->assertInstanceOf('DateInterval', $value);
        $this->assertSame(1, $value->y);
        $this->assertSame(2, $value->h);
    }

    /**
     * Get test formats
     *
     * @return array
     */
    public function getToResourceData(): array
    {
        return [
            ['P1Y', 'P1M', 'P1D', 'PT1H', 'PT1M', 'PT1S', 'P1YT1H' ]
        ];
    }
}