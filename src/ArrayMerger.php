<?php

namespace Graze\ArrayMerger;

use Graze\ArrayMerger\ValueMerger\ArrayMergerInterface;
use Graze\ArrayMerger\ValueMerger\LastValue;

/**
 * Class ArrayMerger
 *
 * This does a simple, `array_merge`, but you can choose how to merge the values yourself
 */
class ArrayMerger implements ArrayMergerInterface
{
    use StaticMethodsTrait;

    /** @var callable */
    protected $valueMerger;

    /**
     * @param callable $valueMerger
     */
    public function __construct(callable $valueMerger = null)
    {
        $this->valueMerger = $valueMerger ?: new LastValue();
    }

    /**
     * Merge using the supplied Value Merge
     *
     * @param callable $valueMerger
     * @param array    $array1
     * @param array    ...$arrays
     *
     * @return array
     */
    public static function mergeUsing(callable $valueMerger, array $array1, array ...$arrays)
    {
        $merger = new static($valueMerger);
        return $merger->merge($array1, ...$arrays);
    }

    /**
     * Merge the values from all the array supplied, the first array is treated as the base array to merge into
     *
     * @param array $array1
     * @param array ...$arrays List of arrays to merge
     *
     * @return array
     */
    public function merge(array $array1, array ...$arrays)
    {
        if (count($arrays) === 0) {
            return $array1;
        }
        $merged = $array1;

        foreach ($arrays as $toMerge) {
            foreach ($toMerge as $key => &$value) {
                if (array_key_exists($key, $merged)) {
                    $merged[$key] = call_user_func($this->valueMerger, $merged[$key], $value);
                } else {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }
}
