<?php

use Statix\Tailor\Tailor;
use Statix\Tailor\Variant;
use Statix\Tailor\VariantsManager;

it('can be created using the new keyword', function () {
    $example = new VariantsManager;

    expect($example)->toBeInstanceOf(VariantsManager::class);
});

// you can optionally pass a name to the constructor
it('can be created with a name', function () {
    $example = new VariantsManager('button');

    expect($example->name)->toBe('button');
});

// the default variant is always created
it('creates a default variant', function () {
    $example = new VariantsManager;

    expect($example->variant('default'))->toBeInstanceOf(Variant::class);
});

// the tailor class is a singleton
it('is a singleton', function () {
    $example = Tailor::getInstance();
    $example2 = Tailor::getInstance();

    expect($example)->toBe($example2);
});

// you can create a new component using the make method
it('can create a new component', function () {
    $example = Tailor::getInstance();

    $example->make('button');

    expect($example->has('button'))->toBeTrue();
});

// if you try to create a component that already exists, it will be retrieved unless you pass true as the second argument
it('will overide a component', function () {
    $example = Tailor::getInstance();

    $example->make('button');

    $component1 = $example->make('button');

    expect($component1)->toBeInstanceOf(VariantsManager::class);

    // should be a new instance b/c overide is true by default
    $component2 = $example->make('button');

    expect($component1)->not->toBe($component2);

    $component3 = $example->make('button', overide: false);

    expect($component2)->toBe($component3);
});

// you can retrieve a component using the get method
it('can retrieve a component', function () {
    $example = Tailor::getInstance();

    $example->make('button');

    $component = $example->get('button');

    expect($component)->toBeInstanceOf(VariantsManager::class);

    $example->make('input');

    $component = $example->get('input');

    expect($component)->toBeInstanceOf(VariantsManager::class);

    expect($example->components())->toHaveCount(2);
});

// you can use the php match function in the methods for adding classes
it('can use the match function', function () {
    $example = new VariantsManager;

    $size = 'sm';

    $example->classes()->add(match ($size) {
        'sm' => 'text-sm',
        'md' => 'text-md',
        'lg' => 'text-lg',
        default => 'text-base',
    });

    expect($example->getClasses())->toBe('text-sm');

    $example = new VariantsManager;

    $size = 'dne';

    $example->classes()->add(match ($size) {
        'sm' => 'text-sm',
        'md' => 'text-md',
        'lg' => 'text-lg',
        default => 'text-base',
    });

    expect($example->getClasses())->toBe('text-base');
});

// you can use the class match function in the methods for adding classes
it('can use the class match function', function () {
    $example = new VariantsManager;

    $size = 'sm';

    $example->classes()->match($size, [
        'sm' => 'text-sm',
        'md' => 'text-md',
        'lg' => 'text-lg',
    ]);

    expect($example->getClasses())->toBe('text-sm');
});

// you can remove classes from a variant
it('can remove classes', function () {
    $example = new VariantsManager;

    $example->classes()->add('text-sm');

    expect($example->getClasses())->toBe('text-sm');

    $example->classes()->remove('text-sm');

    expect($example->getClasses())->toBe('');

    $example->classes()
        ->light([
            'text-sm',
        ])->dark([
            'dark:text-lg',
        ]);

    $example->classes()->remove('text-sm', 'light');

    expect($example->getClasses())->toBe('dark:text-lg');

    $example->classes()->remove('dark:text-lg', 'dark');

    expect($example->getClasses())->toBe('');

    $example->classes()
        ->add('text-white text-large');

    $example->classes()->remove(function () {
        return 'text-white';
    });

    expect($example->getClasses())->toBe('text-large');
});

// it can set attributes
it('can set attributes', function () {
    $example = new VariantsManager;

    $example->attributes()->set('class', 'text-sm');

    expect($example->getAttributes())->toBe('class="text-sm"');

    $example->attributes()->set('class', 'text-sm text-lg');

    expect($example->getAttributes())->toBe('class="text-sm text-lg"');

    $example->attributes()->set('class', ['text-sm', 'text-lg']);

    expect($example->getAttributes())->toBe('class="text-sm text-lg"');

    $example->attributes()->set('class', function () {
        return 'text-sm text-lg';
    });

    expect($example->getAttributes())->toBe('class="text-sm text-lg"');

    $example->attributes()->set([
        'class' => 'text-sm text-lg',
        'id' => 'button',
    ]);

    expect($example->getAttributes())->toBe('class="text-sm text-lg" id="button"');

    $example->attributes()->set([
        'class' => 'text-sm text-lg',
        'id' => 'button',
    ]);

    expect($example->getAttributes())->toBe('class="text-sm text-lg" id="button"');

    $example->variant('dark')->attributes()->set('data', 'dark:text-sm');

    expect($example->variant('dark')->attributes()->get('data'))->toBe('dark:text-sm');
    expect($example->variant('dark')->attributes()->getFormatted('data'))->toBe('data="dark:text-sm"');
});

// you can create subcomponents that have thier own variants
it('can create subcomponents', function () {
    $c = Tailor::getInstance()->make('test');

    $attributes = [
        'id' => 'test-id',
        'placeholder' => 'test-placeholder',
    ];

    $c->classes()
        ->add('text-sm');

    $c->attributes()
        ->set('data-test', 'test')
        ->merge($attributes);

    $c->sub('sub')
        ->classes()
        ->add('text-lg');

    $c->sub('sub')
        ->attributes()
        ->set('data-sub', 'sub');

    $d = Tailor::getInstance()->get('test');
});

// test it sorts the classes correctly
it('sorts the classes', function () {
    $example = new VariantsManager;

    $example->classes()
        ->add('base')
        ->add('base-2');

    expect($example->getClasses())->toBe('base base-2');

    $example->classes()
        ->light('light-1');

    expect($example->getClasses())->toBe('base base-2 light-1');

    $example->classes()
        ->dark('dark-1')
        ->dark('dark-2')
        ->light('light-2');

    expect($example->getClasses())->toBe('base base-2 light-1 light-2 dark-1 dark-2');

    $example->classes()
        ->remove('base');

    expect($example->getClasses())->toBe('base-2 light-1 light-2 dark-1 dark-2');

    $example->classes()
        ->invalid('invalid-1');

    expect($example->getClasses())->toBe('base-2 light-1 light-2 dark-1 dark-2 invalid-1');

    $example->classes()
        ->add('alpha-1', 'alpha');

    expect($example->getClasses())->toBe('base-2 light-1 light-2 dark-1 dark-2 alpha-1 invalid-1');
});

// if using tw merge, it will merge the classes
it('can use tw merge', function () {
    Tailor::getInstance()->enableTailwindMerge(true);

    $example = Tailor::getInstance()->make('button');

    $example->classes()
        ->add('text-sm');

    expect($example->getClasses())->toBe('text-sm');

    $example->classes()
        ->add('text-lg');

    expect($example->getClasses())->toBe('text-lg');

    Tailor::getInstance()->enableTailwindMerge(false);
});

// test you can use a variable in the match function
it('can use a variable in the match function', function () {
    $example = new VariantsManager;

    $size = 'sm';

    $example->classes()->match($size, [
        'sm' => 'text-sm',
        'md' => 'text-md',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
        'default' => $size,
    ]);

    expect($example->getClasses())->toBe('text-sm');

    $example->classes()->reset();

    $size = 'w-32 h-32';

    $example->classes()->match($size, [
        'sm' => 'text-sm',
        'md' => 'text-md',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
        'default' => $size,
    ]);

    expect($example->getClasses())->toBe('w-32 h-32');
});

// it has aria attributes support
test('it has aria attributes support', function () {
    $t = Tailor::getInstance()->make('test.aria');

    $t->aria()
        ->set('label', 'test-label')
        ->set('hidden', 'true');

    $t->variant('primary')
        ->aria()
        ->set('label', 'test-label');

    $t->setVariant('primary');

    expect($t->getAttributes())->toBe('aria-label="test-label" aria-hidden="true" aria-label="test-label"');
});

// you can set onclick attributes
it('can set onclick attributes', function () {
    $example = new VariantsManager;

    $example->attributes()->set('onclick', 'test()');

    expect($example->getAttributes())->toBe('onclick="test()"');

    $example->attributes()->set('onclick', function () {
        return 'test()';
    });

    expect($example->getAttributes())->toBe('onclick="test()"');
});
