<?php

namespace Graze\ArrayMerger\Test\Unit;

use Graze\ArrayMerger\RecursiveArrayMerger;
use Graze\ArrayMerger\Test\TestCase;
use Graze\ArrayMerger\ValueMerger\FirstNonNullValue;
use Graze\ArrayMerger\ValueMerger\FirstValue;
use Graze\ArrayMerger\ValueMerger\LastNonNullValue;
use Graze\ArrayMerger\ValueMerger\LastValue;

class RecursiveArrayMergerTest extends TestCase
{
    public function testSingleArrayReturnsTheArray()
    {
        $merger = new RecursiveArrayMerger();

        $this->assertEquals(
            ['a' => 1, 'b' => 2],
            $merger->merge(['a' => 1, 'b' => 2])
        );
    }

    public function testDefaultFunctionality()
    {
        $a = ['first' => 1, 'second' => 2];
        $b = ['first' => 'a', 'second' => 'b'];

        $merger = new RecursiveArrayMerger();

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

        $merger = new RecursiveArrayMerger();

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

        $merger = new RecursiveArrayMerger(new FirstValue());

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

        $merger = new RecursiveArrayMerger(new FirstValue());

        $this->assertEquals(
            ['first', 'second', 'third', 'fourth'],
            $merger->merge($a, $b),
            "Expected appended array"
        );
        $merger = new RecursiveArrayMerger(new FirstValue(), RecursiveArrayMerger::FLAG_MERGE_VALUE_ARRAY);

        $this->assertEquals(
            ['first', 'second'],
            $merger->merge($a, $b),
            "Expected merged array"
        );
    }

    public function testSequentialChildArraysAreAppended()
    {
        $a = ['first' => ['child' => ['a', 'c', 'd']], 'second' => 2];
        $b = ['first' => ['child' => ['b', 'e']], 'second' => null];

        $merger = new RecursiveArrayMerger(new LastValue());

        $this->assertEquals(
            ['first' => ['child' => ['a', 'c', 'd', 'b', 'e']], 'second' => null],
            $merger->merge($a, $b),
            "Expected appended child array"
        );

        $merger = new RecursiveArrayMerger(new LastValue(), RecursiveArrayMerger::FLAG_MERGE_VALUE_ARRAY);

        $this->assertEquals(
            ['first' => ['child' => ['b', 'e', 'd']], 'second' => null],
            $merger->merge($a, $b),
            "Expected merged child array"
        );
    }

    public function testSequentialTopLevelArraysCanBeUnique()
    {
        $a = ['first', 'second'];
        $b = ['second', 'third'];

        $merger = new RecursiveArrayMerger(new LastValue());

        $this->assertEquals(
            ['first', 'second', 'second', 'third'],
            $merger->merge($a, $b),
            "Expected duplicated array"
        );

        $merger = new RecursiveArrayMerger(new LastValue(), RecursiveArrayMerger::FLAG_UNIQUE_VALUE_ARRAY);

        $this->assertEquals(
            ['first', 'second', 'third'],
            $merger->merge($a, $b),
            "Expected unique array"
        );
    }

    public function testSequentialChildArraysCanBeUnique()
    {
        $a = ['first' => ['child' => ['a', 'c', 'd']], 'second' => 2];
        $b = ['first' => ['child' => ['d', 'e']], 'second' => null];

        $merger = new RecursiveArrayMerger(new FirstValue());

        $this->assertEquals(
            ['first' => ['child' => ['a', 'c', 'd', 'd', 'e']], 'second' => 2],
            $merger->merge($a, $b),
            "Expected merged child array"
        );

        $merger = new RecursiveArrayMerger(new FirstValue(), RecursiveArrayMerger::FLAG_UNIQUE_VALUE_ARRAY);

        $this->assertEquals(
            ['first' => ['child' => ['a', 'c', 'd', 'e']], 'second' => 2],
            $merger->merge($a, $b),
            "Expected appended child array"
        );
    }

    public function testRecursiveFunctionality()
    {
        $a = ['first' => 1, 'second' => ['a' => 2, 'b' => 3]];
        $b = ['first' => 'b', 'second' => ['a' => 'cake']];

        $merger = new RecursiveArrayMerger();

        $this->assertEquals(['first' => 'b', 'second' => ['a' => 'cake', 'b' => 3]], $merger->merge($a, $b));

        $c = ['third' => ['stuff' => 'beep']];

        $this->assertEquals(
            ['first' => 1, 'second' => ['a' => 2, 'b' => 3], 'third' => ['stuff' => 'beep']],
            $merger->merge($a, $c)
        );
    }

    public function testInjectingDifferentValueMerger()
    {
        $merger = new RecursiveArrayMerger(new FirstNonNullValue());

        $a = ['first' => 1, 'second' => null];
        $b = ['first' => 2, 'second' => 'pants'];

        $this->assertEquals(['first' => 1, 'second' => 'pants'], $merger->merge($a, $b));
    }

    public function testMergingMoreThanTwoArrays()
    {
        $merger = new RecursiveArrayMerger(new LastNonNullValue());

        $a = ['first' => 1, 'second' => ['a' => 2, 'b' => 3]];
        $b = ['first' => 'b', 'second' => ['a' => 'cake']];
        $c = ['first' => null, 'second' => ['a' => null, 'c ' => null]];

        $this->assertEquals(
            ['first' => 'b', 'second' => ['a' => 'cake', 'b' => 3, 'c ' => null]],
            $merger->merge($a, $b, $c)
        );
    }

    public function testCallable()
    {
        $merger = new RecursiveArrayMerger('max');

        $a = ['first' => 1, 'second' => 5];
        $b = ['first' => 2, 'second' => 2];

        $this->assertEquals(['first' => 2, 'second' => 5], $merger->merge($a, $b));
    }

    public function testStaticCalling()
    {
        $a = ['first' => 1, 'second' => 2];
        $b = ['first' => 'a', 'second' => 'b'];

        $this->assertEquals(
            ['first' => 'a', 'second' => 'b'],
            RecursiveArrayMerger::mergeUsing(new LastValue(), $a, $b),
            "Expected second result from merge"
        );
    }

    public function testStaticFirstNonNull()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertEquals(
            ['first' => 1, 'second' => 'b'],
            RecursiveArrayMerger::firstNonNull($a, $b),
            "Expected first non null result from merge"
        );
    }

    public function testStaticFirst()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertEquals(
            ['first' => 1, 'second' => null],
            RecursiveArrayMerger::first($a, $b),
            "Expected first result from merge"
        );
    }

    public function testStaticLastNonNull()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertEquals(
            ['first' => 1, 'second' => 'b'],
            RecursiveArrayMerger::lastNonNull($a, $b),
            "Expected last non null result from merge"
        );
    }

    public function testStaticLast()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertEquals(
            ['first' => null, 'second' => 'b'],
            RecursiveArrayMerger::last($a, $b),
            "Expected second result from merge"
        );
    }

    public function testStaticProduct()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => 2, 'second' => 'b'];

        $this->assertEquals(
            ['first' => 2, 'second' => 'b'],
            RecursiveArrayMerger::product($a, $b),
            "Expected product result from merge"
        );
    }

    public function testStaticSum()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => 2, 'second' => 'b'];

        $this->assertEquals(
            ['first' => 3, 'second' => 'b'],
            RecursiveArrayMerger::sum($a, $b),
            "Expected summed result from merge"
        );
    }

    public function testStaticBoth()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertEquals(
            ['first' => [1, null], 'second' => [null, 'b']],
            RecursiveArrayMerger::both($a, $b),
            "Expected both results from merge"
        );
    }

    public function testStaticRandom()
    {
        $a = ['first' => 1, 'second' => null];
        $b = ['first' => null, 'second' => 'b'];

        $this->assertArrayHasKey(
            'first',
            RecursiveArrayMerger::random($a, $b),
            "Expected a random result from merge"
        );
    }
}
