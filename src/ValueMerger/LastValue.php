<?php

namespace Graze\ArrayMerger\ValueMerger;

class LastValue implements ValueMergerInterface
{
    use InvokeMergeTrait;

    /**
     * Always take the last result
     *
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return mixed
     */
    public function merge($value1, $value2)
    {
        return $value2;
    }
}
