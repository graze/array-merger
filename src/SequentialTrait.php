<?php

namespace Graze\ArrayMerger;

trait SequentialTrait
{
    /**
     * Check if the provided array is sequential or not
     *
     * @param array $array
     *
     * @return bool
     */
    protected function isSequential(array $array)
    {
        if (!empty($array) && !array_key_exists(0, $array)) {
            return false;
        }
        return array_values($array) === $array;
    }

    /**
     * @param array $arrays Collection of arrays to check
     *
     * @return bool True if all the arrays are sequential
     */
    protected function areSequential(array $arrays)
    {
        foreach ($arrays as $array) {
            if (!is_array($array) || !$this->isSequential($array)) {
                return false;
            }
        }

        return true;
    }
}
