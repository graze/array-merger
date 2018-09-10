<?php

namespace Graze\ArrayMerger;

use Graze\ArrayMerger\ValueMerger\BothValues;
use Graze\ArrayMerger\ValueMerger\FirstNonNullValue;
use Graze\ArrayMerger\ValueMerger\FirstValue;
use Graze\ArrayMerger\ValueMerger\LastNonNullValue;
use Graze\ArrayMerger\ValueMerger\LastValue;
use Graze\ArrayMerger\ValueMerger\ProductValue;
use Graze\ArrayMerger\ValueMerger\RandomValue;
use Graze\ArrayMerger\ValueMerger\SumValue;

trait StaticMethodsTrait
{
    /**
     * Merge using the FirstNonNull Value Merger
     *
     * @param array $array1
     * @param array ...$arrays
     *
     * @return array
     */
    public static function firstNonNull(array $array1, array ...$arrays)
    {
        return static::mergeUsing(new FirstNonNullValue(), $array1, ...$arrays);
    }

    /**
     * Merge using the First Value Merger
     *
     * @param array $array1
     * @param array ...$arrays
     *
     * @return array
     */
    public static function first(array $array1, array ...$arrays)
    {
        return static::mergeUsing(new FirstValue(), $array1, ...$arrays);
    }

    /**
     * Merge using the LastNonNull Value Merger
     *
     * @param array $array1
     * @param array ...$arrays
     *
     * @return array
     */
    public static function lastNonNull(array $array1, array ...$arrays)
    {
        return static::mergeUsing(new LastNonNullValue(), $array1, ...$arrays);
    }

    /**
     * Merge using the Last Value Merger
     *
     * @param array $array1
     * @param array ...$arrays
     *
     * @return array
     */
    public static function last(array $array1, array ...$arrays)
    {
        return static::mergeUsing(new LastValue(), $array1, ...$arrays);
    }

    /**
     * Merge using the Random Value Merger
     *
     * @param array $array1
     * @param array ...$arrays
     *
     * @return array
     */
    public static function random(array $array1, array ...$arrays)
    {
        return static::mergeUsing(new RandomValue(), $array1, ...$arrays);
    }

    /**
     * Merge using the Sum Value Merger
     *
     * @param array $array1
     * @param array ...$arrays
     *
     * @return array
     */
    public static function sum(array $array1, array ...$arrays)
    {
        return static::mergeUsing(new SumValue(), $array1, ...$arrays);
    }

    /**
     * Merge using the Product Value Merger
     *
     * @param array $array1
     * @param array ...$arrays
     *
     * @return array
     */
    public static function product(array $array1, array ...$arrays)
    {
        return static::mergeUsing(new ProductValue(), $array1, ...$arrays);
    }

    /**
     * Merge using the Product Value Merger
     *
     * @param array $array1
     * @param array ...$arrays
     *
     * @return array
     */
    public static function both(array $array1, array ...$arrays)
    {
        return static::mergeUsing(new BothValues(), $array1, ...$arrays);
    }
}
