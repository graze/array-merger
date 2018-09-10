<?php

namespace Graze\ArrayMerger\ValueMerger;

class LastNonNullValue implements ValueMergerInterface
{
    use InvokeMergeTrait;

    /**
     * Merge the last non null value
     *
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return mixed
     */
    public function merge($value1, $value2)
    {
        return (is_null($value2)) ? $value1 : $value2;
    }
}
