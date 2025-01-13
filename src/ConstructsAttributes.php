<?php

namespace Statix\Tailor;

use BackedEnum;
use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Traits\Macroable;
use Illuminate\View\ComponentAttributeBag;

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

    public function set(
        string|array|ComponentAttributeBag $keys,
        string|array|Closure|BackedEnum|null $values = null): static
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
            // we will map through the values and evaluate them
            // and only if the values are truthy, we will implode them
            // this is useful for setting multiple classes conditionally
            // for example, if we have ['bg-red-500' => true, 'text-white' => false, 'font-bold' => true]
            // we will evaluate each value and only implode the truthy values
            // which will result in 'bg-red-500 font-bold'
            $values = collect($values)->filter(function ($value) {
                return (bool) $value;
            })->keys()->implode(' ');
        }

        $this->attributes[$sanitizedKey] = $values;

        return $this;
    }

    public function merge(array|ComponentAttributeBag $values): static
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

    public function if(mixed $state, mixed $case, Closure $then, ?Closure $else = null): static
    {
        if ($state === $case) {
            app()->call($then, $this->getInjectables());
        } else {
            if ($else) {
                app()->call($else, $this->getInjectables());
            }
        }

        return $this;
    }

    protected function evaluate(
        string|array|ComponentAttributeBag|Closure|BackedEnum|null $value
    ): mixed {
        if ($value instanceof ComponentAttributeBag) {
            return $value->getAttributes();
        }

        if ($value instanceof Closure) {
            return app()->call($value, $this->getInjectables());
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return $value;
    }

    protected function getInjectables(): array
    {
        return [
            'set' => Closure::fromCallable([$this, 'set'])->bindTo($this),
            'get' => Closure::fromCallable([$this, 'get'])->bindTo($this),
            'has' => Closure::fromCallable([$this, 'has'])->bindTo($this),
        ];
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
