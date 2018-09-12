<?php

namespace Graze\ArrayMerger;

trait FlagTrait
{
    /** @var int */
    protected $flags;

    /**
     * Is the provided flag set?
     *
     * @param int $flag
     *
     * @return bool
     */
    protected function isFlagSet($flag)
    {
        return ($this->flags & $flag) === $flag;
    }
}
