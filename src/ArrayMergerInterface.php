<?php

namespace Graze\ArrayMerger;

interface ArrayMergerInterface
{
    /**
     * If 2 elements are both value arrays ['a','b','c'], etc.
     * This will append the second array onto the first.
     *
     * Example:
     *
     * ```php
     * merge(['a' => ['a','b','c']],['b' => ['d','e','f']]);
     * // ['a' => ['a','b','c','d','e','f']]
     * ```
     *
     * If it is off, the first value will be replaced by the second
     */
    const FLAG_APPEND_VALUE_ARRAY = 1;

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
