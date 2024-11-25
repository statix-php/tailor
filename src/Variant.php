<?php

namespace Statix\Tailor;

class Variant
{
    protected VariantsManager $variants;

    protected ConstructsAttributes $attributes;

    protected ConstructsAriaAttributes $aria;

    protected ConstructsClasses $classes;

    public function __construct(protected string $name, VariantsManager $variants)
    {
        $this->variants = $variants;
        $this->attributes = new ConstructsAttributes($this);
        $this->aria = new ConstructsAriaAttributes($this);
        $this->classes = new ConstructsClasses($this);
    }

    public function attributes(): ConstructsAttributes
    {
        return $this->attributes;
    }

    public function aria(): ConstructsAriaAttributes
    {
        return $this->aria;
    }

    public function classes(): ConstructsClasses
    {
        return $this->classes;
    }

    public function variant(string $name): static
    {
        return $this->variants->variant($name);
    }
}
