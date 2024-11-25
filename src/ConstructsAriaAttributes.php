<?php

namespace Statix\Tailor;

use BackedEnum;
use Closure;

class ConstructsAriaAttributes extends ConstructsAttributes
{
    public function role(string|BackedEnum|Closure $role): self
    {
        $role = $this->evaluate($role);

        return $this->set('role', $role);
    }

    public function set(string|array|Closure $keys, string|array|Closure|BackedEnum|null $values = null): static
    {
        $keys = $this->evaluate($keys);
        $values = $this->evaluate($values);

        if (is_array($keys)) {
            foreach ($keys as $key => $value) {
                $this->set('aria-' . $key, $value);
            }

            return $this;
        }

        $sanitizedKey = $this->sanitizeAttributeName('aria-' . $keys);

        if (is_array($values)) {
            $values = collect($values)->map(fn($value) => $this->evaluate($value))->implode(' ');
        }

        $this->attributes[$sanitizedKey] = $values;

        return $this;
    }

    public function autocomplete(string|BackedEnum|Closure $autocomplete): self
    {
        $autocomplete = $this->evaluate($autocomplete);

        return $this->set('aria-autocomplete', $autocomplete);
    }

    public function checked(bool|BackedEnum|Closure $checked): self
    {
        $checked = $this->evaluate($checked);

        return $this->set('aria-checked', $checked);
    }

    public function disabled(bool|BackedEnum|Closure $disabled): self
    {
        $disabled = $this->evaluate($disabled);

        return $this->set('aria-disabled', $disabled);
    }

    public function errormessage(string|BackedEnum|Closure $errormessage): self
    {
        $errormessage = $this->evaluate($errormessage);

        return $this->set('aria-errormessage', $errormessage);
    }

    public function expanded(bool|BackedEnum|Closure $expanded): self
    {
        $expanded = $this->evaluate($expanded);

        return $this->set('aria-expanded', $expanded);
    }

    public function haspopup(string|BackedEnum|Closure $haspopup): self
    {
        $haspopup = $this->evaluate($haspopup);

        return $this->set('aria-haspopup', $haspopup);
    }

    public function hidden(bool|BackedEnum|Closure $hidden): self
    {
        $hidden = $this->evaluate($hidden);

        return $this->set('aria-hidden', $hidden);
    }

    public function invalid(bool|BackedEnum|Closure $invalid): self
    {
        $invalid = $this->evaluate($invalid);

        return $this->set('aria-invalid', $invalid);
    }

    public function label(string|BackedEnum|Closure $label): self
    {
        $label = $this->evaluate($label);

        return $this->set('aria-label', $label);
    }

    public function level(int|BackedEnum|Closure $level): self
    {
        $level = $this->evaluate($level);

        return $this->set('aria-level', $level);
    }

    public function modal(bool|BackedEnum|Closure $modal): self
    {
        $modal = $this->evaluate($modal);

        return $this->set('aria-modal', $modal);
    }

    public function multiline(bool|BackedEnum|Closure $multiline): self
    {
        $multiline = $this->evaluate($multiline);

        return $this->set('aria-multiline', $multiline);
    }

    public function multiselectable(bool|BackedEnum|Closure $multiselectable): self
    {
        $multiselectable = $this->evaluate($multiselectable);

        return $this->set('aria-multiselectable', $multiselectable);
    }

    public function orientation(string|BackedEnum|Closure $orientation): self
    {
        $orientation = $this->evaluate($orientation);

        return $this->set('aria-orientation', $orientation);
    }

    public function placeholder(string|BackedEnum|Closure $placeholder): self
    {
        $placeholder = $this->evaluate($placeholder);

        return $this->set('aria-placeholder', $placeholder);
    }

    public function pressed(bool|BackedEnum|Closure $pressed): self
    {
        $pressed = $this->evaluate($pressed);

        return $this->set('aria-pressed', $pressed);
    }

    public function readonly(bool|BackedEnum|Closure $readonly): self
    {
        $readonly = $this->evaluate($readonly);

        return $this->set('aria-readonly', $readonly);
    }

    public function required(bool|BackedEnum|Closure $required): self
    {
        $required = $this->evaluate($required);

        return $this->set('aria-required', $required);
    }

    public function selected(bool|BackedEnum|Closure $selected): self
    {
        $selected = $this->evaluate($selected);

        return $this->set('aria-selected', $selected);
    }

    public function sort(string|BackedEnum|Closure $sort): self
    {
        $sort = $this->evaluate($sort);

        return $this->set('aria-sort', $sort);
    }

    public function valuemax(float|BackedEnum|Closure $valuemax): self
    {
        $valuemax = $this->evaluate($valuemax);

        return $this->set('aria-valuemax', $valuemax);
    }

    public function valuemin(float|BackedEnum|Closure $valuemin): self
    {
        $valuemin = $this->evaluate($valuemin);

        return $this->set('aria-valuemin', $valuemin);
    }

    public function valuenow(float|BackedEnum|Closure $valuenow): self
    {
        $valuenow = $this->evaluate($valuenow);

        return $this->set('aria-valuenow', $valuenow);
    }

    public function valuetext(string|BackedEnum|Closure $valuetext): self
    {
        $valuetext = $this->evaluate($valuetext);

        return $this->set('aria-valuetext', $valuetext);
    }
}
