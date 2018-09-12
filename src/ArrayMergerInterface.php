<?php

namespace Graze\ArrayMerger;

interface ArrayMergerInterface
{
    /**
     * If 2 elements are both value arrays ['a','b','c'], etc.
     * This will treat the arrays as associative and replace the values using their indexes.
     *
     * Flag off:
     *
     * ```php
     * merge(['a' => ['a','b','c']],['b' => ['d','e','f']]);
     * // ['a' => ['a','b','c','d','e','f']]
     * ```
     *
     * Flag on:
     *
     * ```php
     * merge(['a' => ['a','b','c']],['b' => ['d','e']]);
     * // ['a' => ['d','e','c']]
     * ```
     */
    const FLAG_MERGE_VALUE_ARRAY = 1;

    /**
     * When appending value arrays (if FLAG_MERGE_VALUE_ARRAY is not set) it will include duplicate entries if both
     * arrays have the same value.
     *
     * This flag will remove duplicate values from value arrays
     *
     * Flag off:
     *
     * ```php
     * merge(['a' => ['a','b','c']],['b' => ['c','d','e']]);
     * // ['a' => ['a', 'b', 'c', 'c', 'd', 'e']
     * ```
     *
     * Flag on:
     *
     * ```php
     * merge(['a' => ['a','b','c']],['b' => ['c','d','e']]);
     * // ['a' => ['a', 'b', 'c', 'd', 'e']
     * ```
     */
    const FLAG_UNIQUE_VALUE_ARRAY = 2;

    /**
     * Merge the values from the subsequent set of arrays into the first array
     *
     * @param array $array1
     * @param array $arrays multiple arrays to merge into array1
     *
     * @return array
     */
    public function merge(array $array1, array $arrays = null);
}
