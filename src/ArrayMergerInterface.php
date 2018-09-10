<?php

namespace Graze\ArrayMerger\ValueMerger;

interface ArrayMergerInterface
{
    /**
     * Merge the values from the subsequent set of arrays into the first array
     *
     * @param array $array1
     * @param array ...$arrays
     *
     * @return array
     */
    public function merge(array $array1, array ...$arrays);
}
