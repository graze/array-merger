<?php

namespace Graze\ArrayMerger;

trait FlagTrait
{
    /** @var int */
    private $flags;

    /**
     * Set the flags to be checked against
     *
     * @param int $flags
     */
    protected function setFlags($flags)
    {
        $this->flags = $flags;
    }

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
