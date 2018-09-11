<?php

namespace Graze\ArrayMerger\Test\Unit;

use Graze\ArrayMerger\Test\TestCase;
use Graze\ArrayMerger\ValueMerger\BothValues;
use Graze\ArrayMerger\ValueMerger\FirstNonNullValue;
use Graze\ArrayMerger\ValueMerger\FirstValue;
use Graze\ArrayMerger\ValueMerger\LastNonNullValue;
use Graze\ArrayMerger\ValueMerger\LastValue;
use Graze\ArrayMerger\ValueMerger\ProductValue;
use Graze\ArrayMerger\ValueMerger\RandomValue;
use Graze\ArrayMerger\ValueMerger\SumValue;
use Graze\ArrayMerger\ValueMerger\ValueMergerInterface;

class ValueMergersTest extends TestCase
{
    /**
     * @dataProvider firstDataProvider
     * @dataProvider lastDataProvider
     * @dataProvider firstNonNullDataProvider
     * @dataProvider lastNonNullDataProvider
     * @dataProvider sumDataProvider
     * @dataProvider productDataProvider
     * @dataProvider bothDataProvider
     * @dataProvider randomDataProvider
     *
     * @param ValueMergerInterface $merger
     * @param mixed                $left
     * @param mixed                $right
     * @param mixed                $result
     */
    public function testMerger(ValueMergerInterface $merger, $left, $right, $result)
    {
        $this->assertEquals(
            $result,
            $merger->merge($left, $right),
            "Merger: " . get_class($merger) . " expected: " . print_r($result, true)
        );
    }

    /**
     * @return array
     */
    public function firstDataProvider()
    {
        $merger = new FirstValue();
        return [
            [$merger, "Left", "Right", "Left"],
            [$merger, "Left", null, "Left"],
            [$merger, null, "Right", null],
            [$merger, null, null, null],
        ];
    }

    /**
     * @return array
     */
    public function lastDataProvider()
    {
        $merger = new LastValue();
        return [
            [$merger, "Left", "Right", "Right"],
            [$merger, "Left", null, null],
            [$merger, null, "Right", "Right"],
            [$merger, null, null, null],
        ];
    }

    /**
     * @return array
     */
    public function firstNonNullDataProvider()
    {
        $merger = new FirstNonNullValue();
        return [
            [$merger, "Left", "Right", "Left"],
            [$merger, "Left", null, "Left"],
            [$merger, null, "Right", "Right"],
            [$merger, null, null, null],
        ];
    }

    /**
     * @return array
     */
    public function lastNonNullDataProvider()
    {
        $merger = new LastNonNullValue();
        return [
            [$merger, "Left", "Right", "Right"],
            [$merger, "Left", null, "Left"],
            [$merger, null, "Right", "Right"],
            [$merger, null, null, null],
        ];
    }

    /**
     * @return array
     */
    public function sumDataProvider()
    {
        $merger = new SumValue();
        return [
            [$merger, 1, 2, 3],
            [$merger, 1.5, 2, 3.5],
            [$merger, "left", 2, 2], // defaults to last value merger
            [new SumValue(new FirstValue()), "left", 2, "left"],
        ];
    }

    /**
     * @return array
     */
    public function productDataProvider()
    {
        $merger = new ProductValue();
        return [
            [$merger, 1, 2, 2],
            [$merger, 1.5, 2, 3],
            [$merger, "left", 2, 2], // defaults to last value merger
            [new ProductValue(new FirstValue()), "left", 2, "left"],
        ];
    }

    /**
     * @return array
     */
    public function bothDataProvider()
    {
        $merger = new BothValues();
        return [
            [$merger, "Left", "Right", ["Left", "Right"]],
            [$merger, "Left", null, ["Left", null]],
            [$merger, null, "Right", [null, "Right"]],
            [$merger, null, null, [null, null]],
        ];
    }

    /**
     * @return array
     */
    public function randomDataProvider()
    {
        $merger = new RandomValue(1234);
        if (PHP_VERSION_ID >= 70100) {
            return [
                [$merger, "Left", "Right", "Left"],
                [$merger, "Left", null, "Left"],
                [$merger, null, "Right", "Right"],
            ];
        } else {
            return [
                [$merger, "Left", "Right", "Right"],
                [$merger, "Left", null, "Left"],
                [$merger, null, "Right", null],
            ];
        }
    }
}
