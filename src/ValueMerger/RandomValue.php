<?php

namespace Graze\ArrayMerger\ValueMerger;

class RandomValue implements ValueMergerInterface
{
    use InvokeMergeTrait;

    /**
     * RandomValue constructor.
     *
     * @param int $seed
     */
    public function __construct($seed = null)
    {
        if (!is_null($seed) && is_int($seed)) {
            srand($seed);
        }
    }

    /**
     * Return a random result
     *
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return mixed
     */
    public function merge($value1, $value2)
    {
        return (rand(1, 2) == 1) ? $value1 : $value2;
    }
}
