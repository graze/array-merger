<?php

namespace Graze\ArrayMerger;

use Graze\ArrayMerger\ValueMerger\LastValue;

/**
 * Class RecursiveArrayMerger
 *
 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
 * keys to arrays rather than overwriting the value in the first array with the duplicate
 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
 * this happens (documented behavior):
 *
 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('org value', 'new value'));
 */
class RecursiveArrayMerger implements ArrayMergerInterface
{
    use StaticMethodsTrait;
    use SequentialTrait;

    /** @var callable */
    protected $valueMerger;
    /** @var int */
    private $flags;

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
        $arrays = array_slice(func_get_args(), 1);
        if (count($arrays) === 0) {
            return $array1;
        }

        // if all arrays are sequential and flag is set, append them all
        if ($this->flags & static::FLAG_APPEND_VALUE_ARRAY == static::FLAG_APPEND_VALUE_ARRAY
            && $this->areSequential(array_merge([$array1], $arrays))) {
            return call_user_func_array('array_merge', array_merge([$array1], $arrays));
        }

        $merged = $array1;

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
