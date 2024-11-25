@props([
    'as' => 'button',
    'type' => 'button',
    'size' => 'default',
    'variant' => 'primary',
])

@php
    $c = Tailor::make('button');

    // merge passed attributes and classes
    $c->attributes()->merge($attributes->getAttributes());
    $c->classes()->merge($attributes->get('class', ''));

    $c->attributes()
        ->if($type !== 'button', function () use ($c, $type) {
            $c->attributes()->add('type', $type);
        });

    $c->classes()
        ->base([
            'shadow-sm',
            'font-semibold',
        ])
        ->match($size, [
            'xs' => 'px-2 py-1 text-xs rounded',
            'sm' => 'px-2 py-1 text-sm rounded',
            'default' => 'px-2.5 py-1.5 text-sm rounded-md',
            'lg' => 'px-3 py-2 text-sm rounded-md',
            'xl' => 'px-3.5 py-2.5 text-sm rounded-md',
        ])->focus([
            'focus-visible:outline',
            'focus-visible:outline-2',
            'focus-visible:outline-offset-2',
        ]);

    $c->variant('primary')
        ->classes()
        ->light([
            'bg-indigo-600',
        ])->lightHover([
            'hover:bg-indigo-500',
        ])->lightFocus([
            'focus-visible:outline-offset-2',
            'focus-visible:outline-indigo-600',
        ])->dark([
            'text-white',
        ]);

    $c->setVariant($variant);
@endphp

<{{ $as }} {{ $c }}>
    {{ $slot }}
</{{ $as }}>

<!-- xs -->
<button type="button" class="rounded bg-indigo-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Button text</button>

<!-- sm -->
<button type="button" class="rounded bg-indigo-600 px-2 py-1 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Button text</button>

<!-- default -->
<button type="button" class="rounded-md bg-indigo-600 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Button text</button>

<!-- lg -->
<button type="button" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Button text</button>

<!-- xl -->
<button type="button" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Button text</button>


<{{ $as }} {{ $c }}>
    {{ $slot }}
</{{ $as }}>