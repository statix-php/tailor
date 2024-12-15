<?php

namespace Statix\Tailor;

use BackedEnum;
use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Traits\Macroable;

class ConstructsAttributes implements Htmlable
{
    use Macroable;

    protected $prefix = '';

    protected array $attributes = [];

    public function __construct(protected Variant $tailor) {}

    public function get(?string $key = null): mixed
    {
        if ($key === null) {
            return $this->attributes;
        }

        return $this->attributes[$key] ?? null;
    }

    public function set(string|array|Closure $keys, string|array|Closure|BackedEnum|null $values = null): static
    {
        $keys = $this->evaluate($keys);

        $values = $this->evaluate($values);

        if (is_array($keys)) {
            foreach ($keys as $key => $value) {
                $this->set($key, $value);
            }

            return $this;
        }

        $sanitizedKey = $this->getPrefixedAndSanitizedKey($keys);

        if (is_array($values)) {
            $values = collect($values)->map(fn ($value) => $this->evaluate($value))->implode(' ');
        }

        $this->attributes[$sanitizedKey] = $values;

        return $this;
    }

    public function merge(string|array|Closure $values): static
    {
        $values = $this->evaluate($values);

        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $this->set($key, $value);
            }
        }

        return $this;
    }

    protected function getPrefixedAndSanitizedKey(string $key): string
    {
        $prefix = $this->prefix ? $this->prefix : '';

        return strtolower($prefix.$key);
    }

    public function forget(?string $key = null): static
    {
        if ($key === null) {
            $this->attributes = [];
        } else {
            $key = $this->getPrefixedAndSanitizedKey($key);

            unset($this->attributes[$key]);
        }

        return $this;
    }

    public function reset(?string $key = null): static
    {
        if ($key === null) {
            $this->attributes = [];
        } else {
            $key = $this->getPrefixedAndSanitizedKey($key);

            $this->attributes[$key] = null;
        }

        return $this;
    }

    public function has(string $key): bool
    {
        $key = $this->getPrefixedAndSanitizedKey($key);

        return isset($this->attributes[$key]);
    }

    public function if(bool|Closure $condition, string|array|Closure $attributes): static
    {
        if ($condition instanceof Closure) {
            $condition = app()->call($condition, [
                'variant' => $this->tailor,
                'attributes' => $this,
            ]);
        }

        if ($condition) {
            $attributes = $this->evaluate($attributes);

            if (! is_null($attributes)) {
                $this->set($attributes);
            }
        }

        return $this;
    }

    protected function evaluate(string|array|Closure|BackedEnum|null $value): mixed
    {
        if ($value instanceof Closure) {
            return app()->call($value, [
                'set' => (function (string|array|Closure $keys, string|array|Closure|null $values = null) {
                    return $this->set($keys, $values);
                })->bindTo($this),
                'get' => (function (?string $key = null) {
                    return $this->get($key);
                })->bindTo($this),
                'has' => (function (string $key) {
                    return $this->has($key);
                })->bindTo($this),
            ]);
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return $value;
    }

    public function toHtml(): string
    {
        return (string) $this;
    }

    public function __toString()
    {
        return collect($this->attributes)
            ->sortKeys()
            ->mapWithKeys(fn ($value, $key) => [trim($key) => trim($value)])
            ->map(fn ($value, $key) => $key.'="'.$value.'"')
            ->values()
            ->implode(' ');
    }
}
