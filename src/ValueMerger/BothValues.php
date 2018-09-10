<?php

namespace Graze\ArrayMerger\ValueMerger;

/**
 * This replicates the functionality of array_merge_recursive. It will return an array with both values in it
 */
class BothValues implements ValueMergerInterface
{
    use InvokeMergeTrait;

    /**
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return array|mixed
     */
    public function merge($value1, $value2)
    {
        return [$value1, $value2];
    }
}
