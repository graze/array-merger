<?php

namespace Graze\ArrayMerger\ValueMerger;

interface ValueMergerInterface
{
    /**
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return mixed
     */
    public function __invoke($value1, $value2);

    /**
     * Merge the values $value1 and $value2 and return the result we want
     *
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return mixed
     */
    public function merge($value1, $value2);
}
