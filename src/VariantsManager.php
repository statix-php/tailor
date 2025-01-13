<?php

namespace Statix\Tailor;

use Illuminate\Contracts\Support\Htmlable;
use TailwindMerge\TailwindMerge;

class VariantsManager implements Htmlable
{
    /**
     * The component variants.
     *
     * @var Variant[]
     */
    protected array $variants = [];

    /**
     * The component sub-components.
     *
     * @var VariantsManager[]
     */
    protected array $subComponents = [];

    /**
     * The selected variant.
     *
     * Used to output the correct attributes and classes.
     */
    protected string $selectedVariant = 'default';

    public function __construct(public string $name)
    {
        // Ensure that the default variant is always created
        $this->variant('default');
    }

    /**
     * Create or retrieve a sub-component by name.
     *
     * Sub-components are components that are nested within the main component.
     * For example, a button component may have a sub-component for the icon.
     *
     * The sub-component name should be unique in the context of the main component.
     *
     * The name will be prefixed with the main component name. For example, if the
     * main component is a button and the sub-component is an icon, the sub-component
     * name will be button.icon.
     *
     * @return VariantsManager
     */
    public function sub(string $name): static
    {
        $comptName = $this->name.'.'.$name;

        $comp = new VariantsManager($comptName);

        if (! isset($this->subComponents[$comptName])) {
            $this->subComponents[$comptName] = $comp;
        }

        return $this->subComponents[$comptName];
    }

    /**
     * @see VariantsManager::sub()
     */
    public function child(string $name): static
    {
        return $this->sub($name);
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

    public function hasVariant(string $name): bool
    {
        return isset($this->variants[$name]);
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

        // need to set the variant on all the sub-components
        foreach ($this->subComponents as $subComponent) {
            $subComponent->setVariant($name);
        }

        return $this;
    }

    public function toHtml(): string
    {
        return (string) $this;
    }

    /**
     * Convert the manager to its string representation.
     */
    public function __toString()
    {
        $attributes = $this->compileAttributes();

        return $this->formatAttributes($attributes);
    }

    /**
     * Compile all attributes from default and selected variants.
     */
    protected function compileAttributes(): array
    {
        $defaultVariant = $this->variants['default'];
        $selectedVariant = $this->variants[$this->selectedVariant];

        $attributes = [
            'attributes' => $defaultVariant->attributes()->get(),
            'aria' => $defaultVariant->aria()->get(),
            'data' => $defaultVariant->data()->get(),
            'classes' => $defaultVariant->classes()->get(),
        ];

        // Merge selected variant attributes if not default
        if ($this->selectedVariant !== 'default') {
            $attributes['attributes'] = array_merge($attributes['attributes'], $selectedVariant->attributes()->get());
            $attributes['aria'] = array_merge($attributes['aria'], $selectedVariant->aria()->get());
            $attributes['data'] = array_merge($attributes['data'], $selectedVariant->data()->get());
            $attributes['classes'] = $this->mergeClasses(
                $attributes['classes'],
                $selectedVariant->classes()->get()
            );
        }

        return $attributes;
    }

    /**
     * Merge class lists using appropriate strategy.
     */
    protected function mergeClasses(array $defaultClasses, array $selectedClasses): string
    {
        if (Tailor::getInstance()->isTailwindMergeEnabled()) {
            return TailwindMerge::instance()->merge($defaultClasses, $selectedClasses);
        }

        return collect(array_merge_recursive($defaultClasses, $selectedClasses))
            ->flatten()
            ->unique()
            ->values()
            ->implode(' ');
    }

    /**
     * Format compiled attributes into HTML string.
     */
    protected function formatAttributes(array $attributes): string
    {
        $formatGroup = function (array $group) {
            return collect($group)
                ->sortKeys()
                ->mapWithKeys(fn ($value, $key) => [trim($key) => trim($value)])
                ->map(fn ($value, $key) => $key.'="'.$value.'"')
                ->values()
                ->implode(' ');
        };

        $parts = [
            $formatGroup($attributes['attributes']),
            $formatGroup($attributes['aria']),
            $formatGroup($attributes['data']),
        ];

        $classes = trim(collect($attributes['classes'])->implode(' '));
        if ($classes !== '') {
            $parts[] = sprintf('class="%s"', $classes);
        }

        return preg_replace('/\s+/', ' ', trim(implode(' ', array_filter($parts))));
    }
}
