<?php

namespace Graze\ArrayMerger;

use Graze\ArrayMerger\ValueMerger\LastValue;

class RecursiveArrayMerger implements ArrayMergerInterface
{
    use MergeHelpersTrait;

    /** @var callable */
    protected $valueMerger;

    /**
     * @param callable $valueMerger
     * @param int      $flags one of ArrayMergerInterface::FLAG_*
     */
    public function __construct(callable $valueMerger = null, $flags = 0)
    {
        $this->valueMerger = $valueMerger ?: new LastValue();
        $this->flags = $flags;
    }

    /**
     * Merge using the supplied Value Merge
     *
     * @param callable $valueMerger
     * @param array    $array1
     * @param array    $arrays
     *
     * @return array
     */
    public static function mergeUsing(callable $valueMerger, array $array1, array $arrays)
    {
        $merger = new static($valueMerger);
        return call_user_func_array([$merger, 'merge'], array_merge([$array1], array_slice(func_get_args(), 2)));
    }

    /**
     * Merge the values from all the array supplied, the first array is treated as the base array to merge into
     *
     * @param array      $array1
     * @param array|null $arrays
     *
     * @return array
     */
    public function merge(array $array1, array $arrays = null)
    {
        list($merged, $arrays) = $this->checkSimpleMerge($array1, array_slice(func_get_args(), 1));

        foreach ($arrays as $toMerge) {
            foreach ($toMerge as $key => &$value) {
                if (is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key])) {
                    $merged[$key] = $this->merge($merged[$key], $value);
                } elseif (array_key_exists($key, $merged)) {
                    $merged[$key] = call_user_func($this->valueMerger, $merged[$key], $value);
                } else {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }
}
