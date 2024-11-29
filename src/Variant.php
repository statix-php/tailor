<?php

namespace Statix\Tailor;

class Variant
{
    protected ConstructsAttributes $attributes;

    protected ConstructsAriaAttributes $aria;

    protected ConstructsDataAttributes $data;

    protected ConstructsClasses $classes;

    public function __construct(protected string $name, protected VariantsManager $variants)
    {
        $this->attributes = new ConstructsAttributes($this);
        $this->aria = new ConstructsAriaAttributes($this);
        $this->data = new ConstructsDataAttributes($this);
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

    public function data(): ConstructsDataAttributes
    {
        return $this->data;
    }
}
