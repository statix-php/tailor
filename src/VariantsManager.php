<?php

namespace Statix\Tailor;

use Illuminate\Contracts\Support\Htmlable;

class VariantsManager implements Htmlable
{
    /**
     * @var Variant[]
     */
    protected $variants = [];

    /**
     * @var VariantsManager[]
     */
    protected $subComponents = [];

    protected string $selectedVariant = 'default';

    public function __construct(public ?string $name = null)
    {
        $this->variant('default');
    }

    /**
     * Create or retrieve a sub-component by name.
     */
    public function sub(string $name): static
    {
        $comptName = $this->name . '.' . $name;

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

    /**
     * Access the default variant.
     */
    public function default(): Variant
    {
        return $this->variant('default');
    }

    /**
     * Manage attributes for the default variant.
     */
    public function attributes(): ConstructsAttributes
    {
        return $this->variants['default']->attributes();
    }

    /**
     * Manage classes for the default variant.
     */
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

    /**
     * Get the classes for the component
     */
    public function getClasses()
    {
        $default = (string) $this->variants['default']->classes();

        if ($this->selectedVariant === 'default') {
            $selected = '';
        } else {
            $selected = (string) $this->variants[$this->selectedVariant]->classes();
        }

        // merge the default and selected classes together
        return trim($default . ' ' . $selected);
    }

    public function getAttributes()
    {
        $default = (string) $this->variants['default']->attributes();

        if ($this->selectedVariant === 'default') {
            $selected = '';
        } else {
            $selected = (string) $this->variants[$this->selectedVariant]->attributes();
        }

        // merge the default and selected attributes together
        return trim($default . ' ' . $selected);
    }

    public function toHtml(): string
    {
        return $this->__toString();
    }

    public function __toString()
    {
        $this->attributes()->set('class', $this->getClasses());

        return (string) $this->attributes();
    }
}
