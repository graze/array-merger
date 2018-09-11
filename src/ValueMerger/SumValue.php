<?php

namespace Graze\ArrayMerger\ValueMerger;

/**
 * Add together the contents of the values.
 *
 * Only works if they are both numeric values. If they are not, a backup merger is used (defaults to lastValue)
 *
 * ### Examples:
 *
 * ```
 * 1, 1 -> 2
 * 1.5, 7.2 -> 8.7
 * left, 5 => 5
 * ```
 */
class SumValue implements ValueMergerInterface
{
    use InvokeMergeTrait;

    /** @var ValueMergerInterface */
    private $backup;

    /**
     * SumValueMerger constructor.
     *
     * @param ValueMergerInterface|null $backup
     */
    public function __construct(ValueMergerInterface $backup = null)
    {
        $this->backup = $backup ?: new LastValue();
    }

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
        if (is_numeric($value1) && is_numeric($value2)) {
            return $value1 + $value2;
        }
        return $this->backup->merge($value1, $value2);
    }
}
