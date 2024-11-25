<?php

namespace Statix\Tailor;

class Variant
{
    protected VariantsManager $variants;

    protected ConstructsClasses $classes;

    protected ConstructsAttributes $attributes;

    public function __construct(protected string $name, VariantsManager $variants)
    {
        $this->variants = $variants;
        $this->classes = new ConstructsClasses($this);
        $this->attributes = new ConstructsAttributes($this);
    }

    public function attributes(): ConstructsAttributes
    {
        return $this->attributes;
    }

    public function classes(): ConstructsClasses
    {
        return $this->classes;
    }

    public function variant(string $name): static
    {
        return $this->variants->variant($name);
    }

    // public function __call(string $method, array $arguments): static
    // {
    //     if (method_exists($this->classes, $method)) {
    //         $this->classes->$method(...$arguments);
    //     }

    //     if (method_exists($this->attributes, $method)) {
    //         $this->attributes->$method(...$arguments);
    //     }

    //     return $this;
    // }
}
