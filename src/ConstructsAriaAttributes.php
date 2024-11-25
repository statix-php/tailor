<?php

namespace Statix\Tailor;

use BackedEnum;
use Closure;

class ConstructsAriaAttributes extends ConstructsAttributes
{
    protected $prefix = 'aria-';

    public function role(string|BackedEnum|Closure $role): self
    {
        $role = $this->evaluate($role);

        return $this->set('role', $role);
    }

    public function autocomplete(string|BackedEnum|Closure $autocomplete): self
    {
        $autocomplete = $this->evaluate($autocomplete);

        return $this->set('autocomplete', $autocomplete);
    }

    public function checked(bool|BackedEnum|Closure $checked): self
    {
        $checked = $this->evaluate($checked);

        return $this->set('checked', $checked);
    }

    public function disabled(bool|BackedEnum|Closure $disabled): self
    {
        $disabled = $this->evaluate($disabled);

        return $this->set('disabled', $disabled);
    }

    public function errormessage(string|BackedEnum|Closure $errormessage): self
    {
        $errormessage = $this->evaluate($errormessage);

        return $this->set('errormessage', $errormessage);
    }

    public function expanded(bool|BackedEnum|Closure $expanded): self
    {
        $expanded = $this->evaluate($expanded);

        return $this->set('expanded', $expanded);
    }

    public function haspopup(string|BackedEnum|Closure $haspopup): self
    {
        $haspopup = $this->evaluate($haspopup);

        return $this->set('haspopup', $haspopup);
    }

    public function hidden(bool|BackedEnum|Closure $hidden): self
    {
        $hidden = $this->evaluate($hidden);

        return $this->set('hidden', $hidden);
    }

    public function invalid(bool|BackedEnum|Closure $invalid): self
    {
        $invalid = $this->evaluate($invalid);

        return $this->set('invalid', $invalid);
    }

    public function label(string|BackedEnum|Closure $label): self
    {
        $label = $this->evaluate($label);

        return $this->set('label', $label);
    }

    public function level(int|BackedEnum|Closure $level): self
    {
        $level = $this->evaluate($level);

        return $this->set('level', $level);
    }

    public function modal(bool|BackedEnum|Closure $modal): self
    {
        $modal = $this->evaluate($modal);

        return $this->set('modal', $modal);
    }

    public function multiline(bool|BackedEnum|Closure $multiline): self
    {
        $multiline = $this->evaluate($multiline);

        return $this->set('multiline', $multiline);
    }

    public function multiselectable(bool|BackedEnum|Closure $multiselectable): self
    {
        $multiselectable = $this->evaluate($multiselectable);

        return $this->set('multiselectable', $multiselectable);
    }

    public function orientation(string|BackedEnum|Closure $orientation): self
    {
        $orientation = $this->evaluate($orientation);

        return $this->set('orientation', $orientation);
    }

    public function placeholder(string|BackedEnum|Closure $placeholder): self
    {
        $placeholder = $this->evaluate($placeholder);

        return $this->set('placeholder', $placeholder);
    }

    public function pressed(bool|BackedEnum|Closure $pressed): self
    {
        $pressed = $this->evaluate($pressed);

        return $this->set('pressed', $pressed);
    }

    public function readonly(bool|BackedEnum|Closure $readonly): self
    {
        $readonly = $this->evaluate($readonly);

        return $this->set('readonly', $readonly);
    }

    public function required(bool|BackedEnum|Closure $required): self
    {
        $required = $this->evaluate($required);

        return $this->set('required', $required);
    }

    public function selected(bool|BackedEnum|Closure $selected): self
    {
        $selected = $this->evaluate($selected);

        return $this->set('selected', $selected);
    }

    public function sort(string|BackedEnum|Closure $sort): self
    {
        $sort = $this->evaluate($sort);

        return $this->set('sort', $sort);
    }

    public function valuemax(float|BackedEnum|Closure $valuemax): self
    {
        $valuemax = $this->evaluate($valuemax);

        return $this->set('valuemax', $valuemax);
    }

    public function valuemin(float|BackedEnum|Closure $valuemin): self
    {
        $valuemin = $this->evaluate($valuemin);

        return $this->set('valuemin', $valuemin);
    }

    public function valuenow(float|BackedEnum|Closure $valuenow): self
    {
        $valuenow = $this->evaluate($valuenow);

        return $this->set('valuenow', $valuenow);
    }

    public function valuetext(string|BackedEnum|Closure $valuetext): self
    {
        $valuetext = $this->evaluate($valuetext);

        return $this->set('valuetext', $valuetext);
    }
}
