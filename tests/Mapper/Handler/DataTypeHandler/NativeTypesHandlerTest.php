<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler\DataTypeHandler;

use PHPUnit\Framework\TestCase;

class NativeTypesHandlerTest extends TestCase
{
    /**
     * @dataProvider getGenericData
     *
     * @param string $type
     * @param mixed  $in  Input value
     * @param mixed  $out Output value
     */
    public function testToResourceGeneric(string $type, $in, $out)
    {
        $handler = new NativeTypesHandler();
        $value   = $handler->toResource($in, $type, []);

        $this->assertSame($out, $value);
    }

    /**
     * @dataProvider getGenericData
     *
     * @param string $type
     * @param mixed  $in  Input value
     * @param mixed  $out Output value
     */
    public function testFromResourceGeneric(string $type, $in, $out)
    {
        $handler = new NativeTypesHandler();
        $value   = $handler->fromResource($in, $type, []);

        $this->assertSame($out, $value);
    }

    public function getGenericData(): array
    {
        return [
            // Type      Input  Output
            [ 'string',  1,     '1'  ],
            [ 'boolean', 1,     true ],
            [ 'float',   '1.1', 1.1  ],
            [ 'integer', '1',   1    ],
        ];
    }
}