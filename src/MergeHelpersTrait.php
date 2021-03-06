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

trait MergeHelpersTrait
{
    use SequentialTrait;
    use FlagTrait;

    /**
     * @param array $array1
     * @param array $arrays
     *
     * @return array
     */
    protected function checkSimpleMerge(array $array1, array $arrays = [])
    {
        if (count($arrays) === 0) {
            return [$array1, []];
        }

        // if all arrays are sequential and merge flag is not set, append them all
        if (!$this->isFlagSet(ArrayMergerInterface::FLAG_MERGE_VALUE_ARRAY)
            && $this->areSequential(array_merge([$array1], $arrays))) {
            $merged = call_user_func_array('array_merge', array_merge([$array1], $arrays));
            if ($this->isFlagSet(ArrayMergerInterface::FLAG_UNIQUE_VALUE_ARRAY)) {
                $merged = array_values(array_unique($merged));
            }
            return [$merged, []];
        }

        return [$array1, $arrays];
    }

    /**
     * Merge using the FirstNonNull Value Merger
     *
     * @param array $array1
     * @param array $arrays
     *
     * @return array
     */
    public static function firstNonNull(array $array1, array $arrays)
    {
        return call_user_func_array(
            [static::class, 'mergeUsing'],
            array_merge([new FirstNonNullValue(), $array1], array_slice(func_get_args(), 1))
        );
    }

    /**
     * Merge using the First Value Merger
     *
     * @param array $array1
     * @param array $arrays
     *
     * @return array
     */
    public static function first(array $array1, array $arrays)
    {
        return call_user_func_array(
            [static::class, 'mergeUsing'],
            array_merge([new FirstValue(), $array1], array_slice(func_get_args(), 1))
        );
    }

    /**
     * Merge using the LastNonNull Value Merger
     *
     * @param array $array1
     * @param array $arrays
     *
     * @return array
     */
    public static function lastNonNull(array $array1, array $arrays)
    {
        return call_user_func_array(
            [static::class, 'mergeUsing'],
            array_merge([new LastNonNullValue(), $array1], array_slice(func_get_args(), 1))
        );
    }

    /**
     * Merge using the Last Value Merger
     *
     * @param array $array1
     * @param array $arrays
     *
     * @return array
     */
    public static function last(array $array1, array $arrays)
    {
        return call_user_func_array(
            [static::class, 'mergeUsing'],
            array_merge([new LastValue(), $array1], array_slice(func_get_args(), 1))
        );
    }

    /**
     * Merge using the Random Value Merger
     *
     * @param array $array1
     * @param array $arrays
     *
     * @return array
     */
    public static function random(array $array1, array $arrays)
    {
        return call_user_func_array(
            [static::class, 'mergeUsing'],
            array_merge([new RandomValue(), $array1], array_slice(func_get_args(), 1))
        );
    }

    /**
     * Merge using the Sum Value Merger
     *
     * @param array $array1
     * @param array $arrays
     *
     * @return array
     */
    public static function sum(array $array1, array $arrays)
    {
        return call_user_func_array(
            [static::class, 'mergeUsing'],
            array_merge([new SumValue(), $array1], array_slice(func_get_args(), 1))
        );
    }

    /**
     * Merge using the Product Value Merger
     *
     * @param array $array1
     * @param array $arrays
     *
     * @return array
     */
    public static function product(array $array1, array $arrays)
    {
        return call_user_func_array(
            [static::class, 'mergeUsing'],
            array_merge([new ProductValue(), $array1], array_slice(func_get_args(), 1))
        );
    }

    /**
     * Merge using the Product Value Merger
     *
     * @param array $array1
     * @param array $arrays
     *
     * @return array
     */
    public static function both(array $array1, array $arrays)
    {
        return call_user_func_array(
            [static::class, 'mergeUsing'],
            array_merge([new BothValues(), $array1], array_slice(func_get_args(), 1))
        );
    }
}
