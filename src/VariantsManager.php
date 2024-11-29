<?php

namespace Statix\Tailor;

use Illuminate\Contracts\Support\Htmlable;
use TailwindMerge\TailwindMerge;

class VariantsManager implements Htmlable
{
    /**
     * @var Variant[]
     */
    protected array $variants = [];

    /**
     * @var VariantsManager[]
     */
    protected array $subComponents = [];

    protected string $selectedVariant = 'default';

    public function __construct(public string $name)
    {
        $this->variant('default');
    }

    /**
     * Create or retrieve a sub-component by name.
     */
    public function sub(string $name): static
    {
        $comptName = $this->name.'.'.$name;

        if (! Tailor::getInstance()->has($comptName)) {
            Tailor::getInstance()->make($comptName);
        }

        $comp = Tailor::getInstance()->get($comptName);

        if (! isset($this->subComponents[$comptName])) {
            $this->subComponents[$comptName] = $comp;
        }

        return $this->subComponents[$comptName];
    }

    /**
     * Create or retrieve a variant by name.
     */
    public function variant(string $name): Variant
    {
        if (! isset($this->variants[$name])) {
            $this->variants[$name] = new Variant($name, $this);
        }

        return $this->variants[$name];
    }

    public function default(): Variant
    {
        return $this->variant('default');
    }

    public function attributes(): ConstructsAttributes
    {
        return $this->variants['default']->attributes();
    }

    public function aria(): ConstructsAriaAttributes
    {
        return $this->variants['default']->aria();
    }

    public function data(): ConstructsDataAttributes
    {
        return $this->variants['default']->data();
    }

    public function classes(): ConstructsClasses
    {
        return $this->variants['default']->classes();
    }

    /**
     * Set the selected variant by name in order to access its attributes and classes.
     */
    public function setVariant(string $name): static
    {
        if (! isset($this->variants[$name])) {
            $this->variants[$name] = new Variant($name, $this);
        }

        $this->selectedVariant = $name;

        return $this;
    }

    public function toHtml(): string
    {
        return (string) $this;
    }

    public function __toString()
    {
        // need to compile down all the attributes, classes, and aria attributes
        $attributes = [
            'attributes' => [],
            'aria' => [],
            'data' => [],
            'classes' => [],
        ];

        // we need to add the default variant attributes, aria, and classes
        $defaultVariantAttributes = $this->variants['default']->attributes()->get();
        $defaultVariantAria = $this->variants['default']->aria()->get();
        $defaultVariantData = $this->variants['default']->data()->get();
        $defaultVariantClasses = $this->variants['default']->classes()->get();

        // now we need to check if the selected variant is the default variant
        // if not, we need to add the attributes, aria, and classes from the selected variant
        if ($this->selectedVariant !== 'default') {
            $selectedVariantAttributes = $this->variants[$this->selectedVariant]->attributes()->get();
            $selectedVariantAria = $this->variants[$this->selectedVariant]->aria()->get();
            $selectedVariantData = $this->variants[$this->selectedVariant]->data()->get();
            $selectedVariantClasses = $this->variants[$this->selectedVariant]->classes()->get();

            // now we need to smart merge the attributes, aria, and classes
            // so that the selected variant attributes, aria, and classes override the default variant
            $attributes['attributes'] = array_merge($defaultVariantAttributes, $selectedVariantAttributes);
            $attributes['aria'] = array_merge($defaultVariantAria, $selectedVariantAria);
            $attributes['data'] = array_merge($defaultVariantData, $selectedVariantData);
        } else {
            $selectedVariantClasses = [];

            $attributes['attributes'] = $defaultVariantAttributes;
            $attributes['aria'] = $defaultVariantAria;
            $attributes['data'] = $defaultVariantData;
        }

        // we need to merge the classes together,
        // but we need to check if we are doing a tw-merge or a regular merge
        if (Tailor::getInstance()->tailwindMergeEnabled()) {
            $mergedVariantClasses = TailwindMerge::instance()->merge($defaultVariantClasses, $selectedVariantClasses);
        } else {
            $mergedVariantClasses = array_merge($defaultVariantClasses, $selectedVariantClasses);
        }

        $attributes['classes'] = $mergedVariantClasses;

        // awesome, so now we have all the attributes, aria, and classes. We need to convert
        // them to strings and then merge them together
        $basicAttributes = collect($attributes['attributes'])
            ->sortKeys()
            ->mapWithKeys(fn ($value, $key) => [trim($key) => trim($value)])
            ->map(fn ($value, $key) => $key.'="'.$value.'"')
            ->values()
            ->implode(' ');

        $ariaAttributes = collect($attributes['aria'])
            ->sortKeys()
            ->mapWithKeys(fn ($value, $key) => [trim($key) => trim($value)])
            ->map(fn ($value, $key) => $key.'="'.$value.'"')
            ->values()
            ->implode(' ');

        $dataAttributes = collect($attributes['data'])
            ->sortKeys()
            ->mapWithKeys(fn ($value, $key) => [trim($key) => trim($value)])
            ->map(fn ($value, $key) => $key.'="'.$value.'"')
            ->values()
            ->implode(' ');

        $classes = trim(collect($attributes['classes'])
            ->map(fn ($value) => trim($value))
            ->implode(' '));

        if ($classes !== '') {
            $classes = "class=\"$classes\"";
        }

        $attributes = trim(
            $basicAttributes.' '.$ariaAttributes.' '.$dataAttributes.' '.$classes
        );

        // replace multiple spaces with a single space
        $attributes = preg_replace('/\s+/', ' ', $attributes);

        return $attributes;
    }
}
