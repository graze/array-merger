<?php

namespace Graze\ArrayMerger\ValueMerger;

trait InvokeMergeTrait
{
    /**
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return mixed
     */
    public abstract function merge($value1, $value2);

    /**
     * @param mixed $value1
     * @param mixed $value2
     *
     * @return mixed
     */
    public function __invoke($value1, $value2)
    {
        return $this->merge($value1, $value2);
    }
}
