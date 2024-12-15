<?php

use Statix\Tailor\Tailor;
use Statix\Tailor\Variant;
use Statix\Tailor\VariantsManager;

it('can be created using the new keyword', function () {
    $example = new VariantsManager('new-keyword');

    expect($example)->toBeInstanceOf(VariantsManager::class);
});

// you can optionally pass a name to the constructor
it('can be created with a name', function () {
    $example = new VariantsManager('button');

    expect($example->name)->toBe('button');
});

// the default variant is always created
it('creates a default variant', function () {
    $example = new VariantsManager('default-variant');

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

    expect((string) $t)->toBe('aria-hidden="true" aria-label="test-label"');
});

// you can set multiple attributes and classes and when you cast to a string it will return the html
it('can set multiple attributes and classes', function () {
    $example = new VariantsManager('multi-test');

    $example->attributes()->set([
        'id' => 'button',
    ]);

    $example->classes()->add('text-sm');
    $example->classes()->add('text-lg');

    expect((string) $example)->toBe('id="button" class="text-sm text-lg"');
});

// can set data attributes
it('can set data attributes', function () {
    $example = new VariantsManager('data-test');

    $example->data()->set([
        'id' => 'button',
    ]);

    expect((string) $example)->toBe('data-id="button"');
});

// it does not break when setting attributes with colons in the key
it('does not break when setting attributes with colons in the key', function () {
    $example = new VariantsManager('colon-test');

    $example->attributes()->set([
        'wire:click' => 'action',
    ]);

    expect((string) $example)->toBe('wire:click="action"');
});
