<?php

use Oyova\LaravelSupport\Values\StringContainingOnlyDigits;

$validValues = collect([
    // [input, transformed value]
    [1, '1'],
    [987, '987'],
    ['55555', '55555'],
    ['  1234567  ', '1234567'],
]);

test(
    'valid values',
    fn () => $validValues->each(
        fn ($v) => (
            expect(StringContainingOnlyDigits::getFrom($v[0]))->toBe($v[1])
        )
    )
);

test('invalid values', function () {
    collect([
        '',
        '22 22',
        '333r333',
        1.0,
        'test',
        [],
        new stdclass(),
    ])
        ->each(
            fn ($v) => (
                expect(StringContainingOnlyDigits::isValid($v))->toBeFalse()
            )
        );
});

it(
    'is stringable',
    fn () => $validValues->each(
        fn ($v) => (
            expect((string) StringContainingOnlyDigits::from($v[0]))
                ->toBe((string) $v[1])
        )
    )
);
