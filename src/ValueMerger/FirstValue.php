<?php

namespace Graze\ArrayMerger\ValueMerger;

class FirstValue implements ValueMergerInterface
{
    use InvokeMergeTrait;

    /**
     * Always take the first result
     *
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return mixed
     */
    public function merge($value1, $value2)
    {
        return $value1;
    }
}
