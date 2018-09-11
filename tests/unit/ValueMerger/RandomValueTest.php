<?php

namespace Graze\ArrayMerger\Test\Unit\ValueMerger;

use Graze\ArrayMerger\Test\TestCase;
use Graze\ArrayMerger\ValueMerger\RandomValue;

class RandomValueTest extends TestCase
{
    public function testRandomWithSeed()
    {
        $merger = new RandomValue(2345);

        if (PHP_VERSION_ID >= 70100) {
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('right', $merger('left', 'right'));
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('right', $merger('left', 'right'));
            $this->assertEquals('right', $merger('left', 'right'));
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('left', $merger('left', 'right'));
        } else {
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('left', $merger('left', 'right'));
            $this->assertEquals('right', $merger('left', 'right'));
            $this->assertEquals('right', $merger('left', 'right'));
            $this->assertEquals('right', $merger('left', 'right'));
            $this->assertEquals('right', $merger('left', 'right'));
        }
    }
}
