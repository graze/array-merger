<?php

namespace Graze\ArrayMerger;

use Graze\ArrayMerger\ValueMerger\LastValue;

/**
 * Class ArrayMerger
 *
 * This does a simple, `array_merge`, but you can choose how to merge the values yourself
 */
class ArrayMerger implements ArrayMergerInterface
{
    use StaticMethodsTrait;
    use SequentialTrait;

    /** @var callable */
    protected $valueMerger;
    /** @var int */
    private $flags;

    /**
     * @param callable $valueMerger
     * @param int      $flags
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
                if (array_key_exists($key, $merged)) {
                    if ($this->flags & static::FLAG_APPEND_VALUE_ARRAY == static::FLAG_APPEND_VALUE_ARRAY
                        && $this->areSequential([$value, $merged[$key]])) {
                        $merged[$key] = array_merge($merged[$key], $value);
                    } else {
                        $merged[$key] = call_user_func($this->valueMerger, $merged[$key], $value);
                    }
                } else {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }
}
