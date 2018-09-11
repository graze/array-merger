<?php

namespace Graze\ArrayMerger\Test\Unit;

use Graze\ArrayMerger\Test\FakeSequential;
use Graze\ArrayMerger\Test\TestCase;

class SequentialTest extends TestCase
{
    /**
     * @dataProvider sequentialArrayData
     *
     * @param array $input
     * @param bool  $expected
     */
    public function testArray(array $input, $expected)
    {
        $sequential = new FakeSequential();
        $this->assertEquals($expected, $sequential->isSequentialPublic($input));
    }

    /**
     * @return array
     */
    public function sequentialArrayData()
    {
        return [
            [
                [
                    [
                        'foo' => 'bar',
                    ],
                ],
                true,
            ],
            [
                [
                    [
                        'bar',
                        'foo' => 'bar',
                        'baz',
                    ],
                ],
                true,
            ],
            [[null], true],
            [[true], true],
            [[false], true],
            [[0], true],
            [[1], true],
            [[0.0], true],
            [[1.0], true],
            [['string'], true],
            [[[0, 1, 2]], true],
            [[new \stdClass()], true],
            [['a' => 'b'], false],
            [[1 => 'a'], false],
            [[1 => 'a', 'c'], false],
            [array_fill(0, 1000, uniqid()), true],                // big numeric array
            [array_fill_keys(range(2, 1000, 3), uniqid()), false],  // big misaligned numeric array (=associative)
            [
                array_fill_keys(                            // big associative array
                    str_split(
                        str_repeat(uniqid('', true), 100),
                        3
                    ),
                    true
                ),
                false,
            ],
        ];
    }

    /**
     * @dataProvider multipleArrayData
     *
     * @param array $inputs
     * @param bool  $expected
     */
    public function testMultipleArrays(array $inputs, $expected)
    {
        $sequential = new FakeSequential();
        $this->assertEquals($expected, $sequential->areSequentialPublic($inputs));
    }

    /**
     * @return array
     */
    public function multipleArrayData()
    {
        return [
            [[['a', 'b'], ['c', 'd']], true],
            [[['a', 'b'], ['c', 'd'], ['e', 'f']], true],
            [[['a', 'b']], true],
            [[['a' => 'b']], false],
            [[['a' => 'b'], ['c', 'd']], false],
        ];
    }
}
