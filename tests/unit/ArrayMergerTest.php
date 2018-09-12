<?php

namespace Graze\ArrayMerger\Test\Unit;

use Graze\ArrayMerger\ArrayMerger;
use Graze\ArrayMerger\Test\TestCase;
use Graze\ArrayMerger\ValueMerger\FirstNonNullValue;
use Graze\ArrayMerger\ValueMerger\FirstValue;
use Graze\ArrayMerger\ValueMerger\LastNonNullValue;
use Graze\ArrayMerger\ValueMerger\LastValue;

class ArrayMergerTest extends TestCase
{
    public function testSingleArrayReturnsTheArray()
    {
        $merger = new ArrayMerger();

        $this->assertEquals(
            ['a' => 1, 'b' => 2],
            $merger->merge(['a' => 1, 'b' => 2])
        );
    }

    public function testDefaultFunctionality()
    {
        $a = ['first' => 1, 'second' => 2];
        $b = ['first' => 'a', 'second' => 'b'];

        $merger = new ArrayMerger();

        $this->assertEquals(
            ['first' => 'a', 'second' => 'b'],
            $merger->merge($a, $b),
            "Expected second result from merge"
        );

        $c = ['first' => 'c'];

        $this->assertEquals(['first' => 'c', 'second' => 2], $merger->merge($a, $c), "Expected Merge of 'a' and 'c'");
    }

    public function testNullsAreValuesWhenSecond()
    {
        $a = ['first' => 1, 'second' => 2];
        $b = ['first' => 'a', 'second' => null];

        $merger = new ArrayMerger();

        $this->assertEquals(
            ['first' => 'a', 'second' => null],
            $merger->merge($a, $b),
            "Expected second result from merge"
        );
    }

    public function testNullsAreValuesWhenFirst()
    {
        $a = ['first' => null, 'second' => 2];
        $b = ['first' => 'a', 'second' => null];

        $merger = new ArrayMerger(new FirstValue());

        $this->assertEquals(
            ['first' => null, 'second' => 2],
            $merger->merge($a, $b),
            "Expected first result from merge"
        );
    }

    public function testSequentialTopLevelArraysAreAppended()
    {
        $a = ['first', 'second'];
        $b = ['third', 'fourth'];

        $merger = new ArrayMerger(new FirstValue());

        $this->assertEquals(
            ['first', 'second', 'third', 'fourth'],
            $merger->merge($a, $b),
            "Expected appended array"
        );
        $merger = new ArrayMerger(new FirstValue(), ArrayMerger::FLAG_MERGE_VALUE_ARRAY);

        $this->assertEquals(
            ['first', 'second'],
            $merger->merge($a, $b),
            "Expected a non appended array when the flag is not set"
        );
    }

    public function testSequentialChildArraysAreNotAppendedBecauseThisIsNotRecursive()
    {
        $a = ['first' => ['a', 'c', 'd'], 'second' => 2];
        $b = ['first' => ['b', 'e'], 'second' => null];

        $merger = new ArrayMerger(new LastValue());

        $this->assertEquals(
            ['first' => ['b', 'e'], 'second' => null],
            $merger->merge($a, $b),
            "Expected appended child array"
        );

        $merger = new ArrayMerger(new LastValue(), ArrayMerger::FLAG_MERGE_VALUE_ARRAY);

        $this->assertEquals(
            ['first' => ['b', 'e'], 'second' => null],
            $merger->merge($a, $b),
            "Expected merged child array"
        );
    }

    public function testSequentialTopLevelArraysCanBeUnique()
    {
        $a = ['first', 'second'];
        $b = ['second', 'third'];

        $merger = new ArrayMerger(new FirstValue());

        $this->assertEquals(
            ['first', 'second', 'second', 'third'],
            $merger->merge($a, $b),
            "Expected duplicated second value"
        );

        $merger = new ArrayMerger(new FirstValue(), ArrayMerger::FLAG_UNIQUE_VALUE_ARRAY);

        $this->assertEquals(
            ['first', 'second', 'third'],
            $merger->merge($a, $b),
            "Expected unique output"
        );
    }

    public function testNonRecursiveFunctionality()
    {
        $a = ['first' => 1, 'second' => ['a' => 2, 'b' => 3]];
        $b = ['first' => 'b', 'second' => ['a' => 'cake']];

        $merger = new ArrayMerger();

        $this->assertEquals(['first' => 'b', 'second' => ['a' => 'cake']], $merger->merge($a, $b));

        $c = ['third' => ['stuff' => 'beep']];

        $this->assertEquals(
            ['first' => 1, 'second' => ['a' => 2, 'b' => 3], 'third' => ['stuff' => 'beep']],
            $merger->merge($a, $c)
        );
    }

    public function testInjectingDifferentValueMerger()
    {
        $merger = new ArrayMerger(new FirstNonNullValue());

        $a = ['first' => 1, 'second' => null];
        $b = ['first' => 2, 'second' => 'pants'];

        $this->assertEquals(['first' => 1, 'second' => 'pants'], $merger->merge($a, $b));
    }

    public function testMergingMoreThanTwoArrays()
    {
        $merger = new ArrayMerger(new LastNonNullValue());

        $a = ['first' => 1, 'second' => null];
        $b = ['first' => 2, 'second' => 'pants'];
        $c = ['first' => null, 'second' => null, 'third' => 3];

        $this->assertEquals(
            ['first' => 2, 'second' => 'pants', 'third' => 3],
            $merger->merge($a, $b, $c)
        );
    }

    public function testStaticCalling()
    {
        $a = ['first' => 1, 'second' => 2];
        $b = ['first' => 'a', 'second' => 'b'];

        $this->assertEquals(
            ['first' => 'a', 'second' => 'b'],
            ArrayMerger::mergeUsing(new LastValue(), $a, $b),
            "Expected second result from merge"
        );
    }

    public function testStaticFirstNonNull()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertEquals(
            ['first' => 1, 'second' => 'b'],
            ArrayMerger::firstNonNull($a, $b),
            "Expected first non null result from merge"
        );
    }

    public function testStaticFirst()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertEquals(
            ['first' => 1, 'second' => null],
            ArrayMerger::first($a, $b),
            "Expected first result from merge"
        );
    }

    public function testStaticLastNonNull()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertEquals(
            ['first' => 1, 'second' => 'b'],
            ArrayMerger::lastNonNull($a, $b),
            "Expected last non null result from merge"
        );
    }

    public function testStaticLast()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertEquals(
            ['first' => null, 'second' => 'b'],
            ArrayMerger::last($a, $b),
            "Expected second result from merge"
        );
    }

    public function testStaticProduct()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => 2, 'second' => 'b'];

        $this->assertEquals(
            ['first' => 2, 'second' => 'b'],
            ArrayMerger::product($a, $b),
            "Expected product result from merge"
        );
    }

    public function testStaticSum()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => 2, 'second' => 'b'];

        $this->assertEquals(
            ['first' => 3, 'second' => 'b'],
            ArrayMerger::sum($a, $b),
            "Expected summed result from merge"
        );
    }

    public function testStaticBoth()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertEquals(
            ['first' => [1, null], 'second' => [null, 'b']],
            ArrayMerger::both($a, $b),
            "Expected both results from merge"
        );
    }

    public function testStaticRandom()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertArrayHasKey(
            'first',
            ArrayMerger::random($a, $b),
            "Expected a random result from merge"
        );
    }
}
