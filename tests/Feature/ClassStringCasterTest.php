<?php

// test that the class string caster sorts the classes correctly

use Statix\Tailor\Casters\ClassStringCaster;

test('the class string caster sorts the classes correctly', function () {
    $caster = new ClassStringCaster;

    $classes = [
        'light' => [
            'text-gray-100',
            'bg-gray-900',
        ],
        'base' => [
            'text-white',
            'bg-black',
        ],
        'dark' => [
            'dark:text-gray-900',
            'dark:bg-gray-100',
        ],
        'focus' => [
            'focus:outline-none',
        ],
    ];

    $expected = 'bg-black text-white bg-gray-900 text-gray-100 dark:bg-gray-100 dark:text-gray-900 focus:outline-none';

    $actual = $caster->toString($classes);

    expect($actual)->toBe($expected);
});
