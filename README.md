# Array Merger

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graze/array-merger.svg?style=flat-square)](https://packagist.org/packages/graze/array-merger)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/graze/array-merger/master.svg?style=flat-square)](https://travis-ci.org/graze/array-merger)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/graze/array-merger.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/array-merger/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/graze/array-merger.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/array-merger)
[![Total Downloads](https://img.shields.io/packagist/dt/graze/array-merger.svg?style=flat-square)](https://packagist.org/packages/graze/array-merger)

Array Merger allows you to recursively merge arrays and choose how the values should be merged.

![Merge](https://media.giphy.com/media/cnEXDpXvkZ7lm/giphy.gif)

## Why

The php function: [`array_merge_recursive`](http://php.net/manual/en/function.array-merge-recursive.php)
does indeed merge arrays, but it converts values with duplicate keys to arrays rather than overwriting the value in
the first array with the duplicate value in the second array, as array_merge does. I.e., with array_merge_recursive,
this happens (documented behaviour).

```php
array_merge_recursive(['key' => 'org value', 'key2' => 'first'], ['key' => 'new value', 'key2' => null]);
// ['key' => ['org value', 'new value'], 'key2' => ['first', null]];
```

There is also [`array_replace_recursive`](http://php.net/manual/en/function.array-replace-recursive.php)
which replaces values in the first with values in the second, but it handles value arrays differently and only supports
replacing with the last value.

```php
array_replace_recursive(['key' => 'org value', 'key2' => 'first'], ['key' => 'new value', 'key2' => null]);
// ['key' => 'new value', 'key2' => null];
```

This library gives you the flexibility to ensure you get the values you actually want in the merge.

```php
RecursiveArrayMerger::lastNonNull(['key' => 'org value', 'key2' => 'first'], ['key' => 'new value', 'key2' => null]);
// ['key' => 'new value', 'key2' => 'first']);
```

## Install

Via Composer

```bash
composer require graze/array-merger
```

## Value Mergers

- **LastValue**: Takes the last value (default) _equivalent to `array_replace_recursive`_
- **LastNonNullValue**: Takes the last value, unless it is null then the first
- **FirstValue**: Takes the first value
- **FirstNonNullValue**: Takes the first value, unless it is null, then the second
- **RandomValue**: Takes a random value
- **SumValue**: If both values are numeric, will add them together
- **ProductValue**: If both values are numeric, will multiply them together
- **BothValues**: Will return both values in an array, _equivalent to `array_merge_recursive`_

## Usage

There is an `ArrayMerger` which will only merge at the top level, and a `RecursiveArrayMerger` which will merge
recursively.

The mergers can take any number of arguments, and will treat the first argument as the base array to merge the
subsequent arrays into.

```php
$merger = new Graze\ArrayMerger\ArrayMerger();
$merger->merge(
    ['a' => 'first', 'b' => ['c' => 'cake', 'd' => 'fish']],
    ['a' => 'second', 'b' => ['d' => 'money']]
);
// ['a' => 'second', 'b' => ['d' => 'money']]

$merger = new Graze\ArrayMerger\RecursiveArrayMerger();
$merger->merge(
    ['a' => 'first', 'b' => ['c' => 'cake', 'd' => 'fish']],
    ['a' => 'second', 'b' => ['d' => 'money']],
    ['a' => 'third', 'b' => ['e' => 'planets']],
);
// ['a' => 'third', 'b' => ['c' => 'cake', 'd' => 'money', 'e' => 'planets]]
```

### Supplying a Value Merger

By default the last value will be used when merging, however you can supply a different [Value Merger](#value-mergers)
to change the behaviour of the merged value.

```php
$merger = new Graze\ArrayMerger\RecursiveArrayMerger(new LastNonNullValue());
$merger->merge(
    ['a' => 'first', 'b' => ['c' => 'cake', 'd' => 'fish']],
    ['a' => 'second', 'b' => ['d' => null]]
);

// ['a' => 'second', 'b' => ['c' => 'cake', 'd' => 'fish']]
```

The Value Merger is a `callable` that can take 2 arguments. This allows you to use in-built and in-line functions:

```php
$merger = new Graze\ArrayMerger\RecursiveArrayMerger('max');
$merger->merge(
    ['a' => 1, 'b' => ['c' => 2, 'd' => 3]],
    ['a' => 4, 'b' => ['d' => 1]]
);
// ['a' => 4, 'b' => ['c' => 2, 'd' => 3]]

// or some strange value choose of your choice
$merger = new Graze\ArrayMerger\RecursiveArrayMerger(
    function ($a, $b) {
        return $a % $b == 0 ? $a : $b;
    }
);
$merger->merge(
    ['a' => 1, 'b' => ['c' => 2, 'd' => 3]],
    ['a' => 4, 'b' => ['d' => 1]]
);
// ['a' => 1, 'b' => ['c' => 2, 'd' => 0]]
```

### Calling Statically

You can call merge statically and specify the Value Merger of your choice:

```php
RecursiveArrayMerger::mergeUsing(
    new LastValue(),
    ['a' => 'first', 'b' => ['c' => 'cake', 'd' => 'fish']],
    ['a' => 'second', 'b' => ['d' => 'money']]
);
// ['a' => 'second', 'b' => ['c' => 'cake', 'd' => 'money']]
```

Each of the supplied value mergers also have static helper methods to call them:

```php
RecursiveArrayMerger::lastNonNull(
    ['a' => 'first', 'b' => ['c' => 'cake', 'd' => 'fish']],
    ['a' => null, 'b' => ['d' => 'money']]
);
// ['a' => 'first', 'b' => ['c' => 'cake', 'd' => 'money']]
```

### Value Arrays

By default value arrays (arrays with no indexes supplied) will be appended onto each other, if you want to treat them
as associative arrays, you can supply this flag: `RecursiveArrayMerger::FLAG_MERGE_VALUE_ARRAY`.

```php
$a = ['a' => 'first', 'b' => ['a','c','d']];
$b = ['a' => 'second', 'b' => ['e']];
$merger = new Graze\ArrayMerger\RecursiveArrayMerger(new LastValue());
$merger->merge($a,$b);
// ['a' => 'second', 'b' => ['a','c','d','e']]

$merger = new Graze\ArrayMerger\RecursiveArrayMerger(new LastValue(), RecursiveArrayMerger::FLAG_MERGE_VALUE_ARRAY);
$merger->merge($a,$b);
// ['a' => 'second', 'b' => ['e','c','d']]
```

#### Unique Values

By default, when we append value arrays it will keep duplicate values. If you want to remove the duplicate values you
can supply the flag: `RecursiveArrayMerger::FLAG_UNIQUE_VALUE_ARRAY`.
This flag will only be relevant if the `RecursiveArrayMerger::FLAG_MERGE_VALUE_ARRAY` flag is not supplied.

```php
$a = ['a' => 'first', 'b' => ['a','c','d']];
$b = ['a' => 'second', 'b' => ['d','e']];
$merger = new Graze\ArrayMerger\RecursiveArrayMerger(new LastValue());
$merger->merge($a,$b);
// ['a' => 'second', 'b' => ['a','c','d','d','e']]

$merger = new Graze\ArrayMerger\RecursiveArrayMerger(new LastValue(), RecursiveArrayMerger::FLAG_UNIQUE_VALUE_ARRAY);
$merger->merge($a,$b);
// ['a' => 'second', 'b' => ['a','c','d','e']]
```

## Testing

```shell
make build test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email security@graze.com instead of using the issue tracker.

## Credits

- [Harry Bragg](https://github.com/h-bragg)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
