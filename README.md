# Array Merger

[![Latest Version on Packagist](https://img.shields.io/packagist/v/graze/array-merger.svg?style=flat-square)](https://packagist.org/packages/graze/array-merger)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/graze/array-merger/master.svg?style=flat-square)](https://travis-ci.org/graze/array-merger)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/graze/array-merger.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/array-merger/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/graze/array-merger.svg?style=flat-square)](https://scrutinizer-ci.com/g/graze/array-merger)
[![Total Downloads](https://img.shields.io/packagist/dt/graze/array-merger.svg?style=flat-square)](https://packagist.org/packages/graze/array-merger)

Array Merge allows you to recursively merge arrays and choose how the values should be merged.

![Merge](https://media.giphy.com/media/cnEXDpXvkZ7lm/giphy.gif)

## Why

The php function: [`array_merge_recursive`](http://php.net/manual/en/function.array-merge-recursive.php)
does indeed merge arrays, but it converts values with duplicate keys to arrays rather than overwriting the value in
the first array with the duplicate value in the second array, as array_merge does. I.e., with array_merge_recursive,
this happens (documented behavior):

```php
array_merge_recursive(['key' => 'org value'], ['key' => 'new value']);
// ['key' => ['org value', 'new value']];
```

This allows you to get the value you actually want

```php
RecursiveArrayMerger::last(['key' => 'org value'], ['key' => 'new value']);
// ['key' => 'new value'];
```

## Install

Via Composer

```bash
composer require graze/array-merger
```

## Value Mergers

- **LastValue**: Takes the last value
- **LastNonNullValue**: Takes the last value, unless it is null then the first
- **FirstValue**: Takes the first value
- **FirstNonNullValue**: Takes the first value, unless it is null, then the second
- **RandomValue**: Takes a random value
- **SumValue**: If both values are numeric, will add them together
- **ProductValue**: If both values are numeric, will multiply them together
- **BothValues**: Will return both values in an array, (same as `array_merge_recursive`)

## Usage

There is an `ArrayMerger` which will only merge at the top level, and a `RecursiveArrayMerger` which will merge
recursively.

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
    ['a' => 'second', 'b' => ['d' => 'money']]
);
// ['a' => 'second', 'b' => ['c' => 'cake', 'd' => 'money']]
```

### Calling Statically

```php
RecursiveArrayMerger::mergeUsing(
    new LastValue(),
    ['a' => 'first', 'b' => ['c' => 'cake', 'd' => 'fish']],
    ['a' => 'second', 'b' => ['d' => 'money']]
);
// ['a' => 'second', 'b' => ['c' => 'cake', 'd' => 'money']]

RecursiveArrayMerger::lastNonNull(
    ['a' => 'first', 'b' => ['c' => 'cake', 'd' => 'fish']],
    ['a' => null', 'b' => ['d' => 'money']]
);
// ['a' => 'first', 'b' => ['c' => 'cake', 'd' => 'money']]
```

### Supplying a Value Merger

```php
$merger = new Graze\ArrayMerger\RecursiveArrayMerger(new LastNonNullValue());
$merger->merge(
    ['a' => 'first', 'b' => ['c' => 'cake', 'd' => 'fish']],
    ['a' => 'second', 'b' => ['d' => null]]
);

// ['a' => 'second', 'b' => ['c' => 'cake', 'd' => 'first']]
```

The Value Merger is just a callable that can take 2 arguments. This allows you to do many different comparisons:

```php
RecursiveArrayMerger::mergeUsing(
    'max',
    ['a' => 1, 'b' => ['c' => 2, 'd' => 3]],
    ['a' => 4, 'b' => ['d' => 1]]
);
// ['a' => 4, 'b' => ['c' => 2, 'd' => 3]]
```

### Sequential Arrays

If instead of overwriting sequential arrays you can append them using a flag

```php
$merger = new Graze\ArrayMerger\RecursiveArrayMerger(new LastValue(), RecursiveArrayMerger::FLAG_APPEND_VALUE_ARRAY);
$merger->merge(
    ['a' => 'first', 'b' => ['a','c','d']],
    ['a' => 'second', 'b' => ['e']]
);
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
