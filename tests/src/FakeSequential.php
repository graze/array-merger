<?php

namespace Graze\ArrayMerger\Test;

use Graze\ArrayMerger\SequentialTrait;

class FakeSequential
{
    use SequentialTrait;

    /**
     * @param array $array
     *
     * @return bool
     */
    public function isSequentialPublic(array $array)
    {
        return $this->isSequential($array);
    }

    /**
     * @param array $arrays
     *
     * @return bool
     */
    public function areSequentialPublic(array $arrays)
    {
        return $this->areSequential($arrays);
    }
}
