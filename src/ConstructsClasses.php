<?php

namespace Statix\Tailor;

use BackedEnum;
use Closure;
use Exception;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Traits\Macroable;
use TailwindMerge\TailwindMerge;

class ConstructsClasses implements Htmlable
{
    use Macroable;

    protected array $classes = [];

    /**
     * The flag to determine if we are using TailwindMerge.
     */
    protected bool $usingTailwindMerge;

    /**
     * The TailwindMerger instance if enabled.
     */
    protected $twMerger = null;

    public function __construct(protected Variant $tailor)
    {
        $this->usingTailwindMerge = Tailor::getInstance()->usingTailwindMerge();

        if ($this->usingTailwindMerge) {
            $this->twMerger = TailwindMerge::instance();
        }
    }

    public function base(string|array|Closure $classes): static
    {
        $this->state('base', $classes);

        return $this;
    }

    public function default(string|array|Closure $classes): static
    {
        $this->base($classes);

        return $this;
    }

    public function add(string|array|Closure $classes, string $key = 'base'): static
    {
        $this->state($key, $classes);

        return $this;
    }

    public function merge(string|array|Closure $classes, string $key = 'base'): static
    {
        $this->state($key, $classes);

        return $this;
    }

    public function light(string|array|Closure $classes): static
    {
        $this->state('light', $classes);

        return $this;
    }

    public function dark(string|array|Closure $classes): static
    {
        $this->state('dark', $classes);

        return $this;
    }

    public function focus(string|array|Closure $classes): static
    {
        $this->state('focus', $classes);

        return $this;
    }

    public function focusLight(string|array|Closure $classes): static
    {
        $this->state('focus', $classes);

        return $this;
    }

    public function focusDark(string|array|Closure $classes): static
    {
        $this->state('focus', $classes);

        return $this;
    }

    public function hover(string|array|Closure $classes): static
    {
        $this->state('hover', $classes);

        return $this;
    }

    public function hoverLight(string|array|Closure $classes): static
    {
        $this->state('hover', $classes);

        return $this;
    }

    public function hoverDark(string|array|Closure $classes): static
    {
        $this->state('hover', $classes);

        return $this;
    }

    public function disabled(string|array|Closure $classes): static
    {
        $this->state('disabled', $classes);

        return $this;
    }

    public function disabledLight(string|array|Closure $classes): static
    {
        $this->state('disabled', $classes);

        return $this;
    }

    public function disabledDark(string|array|Closure $classes): static
    {
        $this->state('disabled', $classes);

        return $this;
    }

    public function error(string|array|Closure $classes): static
    {
        $this->state('error', $classes);

        return $this;
    }

    public function errorLight(string|array|Closure $classes): static
    {
        $this->state('error', $classes);

        return $this;
    }

    public function errorDark(string|array|Closure $classes): static
    {
        $this->state('error', $classes);

        return $this;
    }

    public function valid(string|array|Closure $classes): static
    {
        $this->state('valid', $classes);

        return $this;
    }

    public function validLight(string|array|Closure $classes): static
    {
        $this->state('valid', $classes);

        return $this;
    }

    public function validDark(string|array|Closure $classes): static
    {
        $this->state('valid', $classes);

        return $this;
    }

    public function invalid(string|array|Closure $classes): static
    {
        $this->state('invalid', $classes);

        return $this;
    }

    public function invalidLight(string|array|Closure $classes): static
    {
        $this->state('invalid', $classes);

        return $this;
    }

    public function invalidDark(string|array|Closure $classes): static
    {
        $this->state('invalid', $classes);

        return $this;
    }

    public function readOnly(string|array|Closure $classes): static
    {
        $this->state('readOnly', $classes);

        return $this;
    }

    public function readOnlyLight(string|array|Closure $classes): static
    {
        $this->state('readOnly', $classes);

        return $this;
    }

    public function readOnlyDark(string|array|Closure $classes): static
    {
        $this->state('readOnly', $classes);

        return $this;
    }

    public function print(string|array|Closure $classes): static
    {
        $this->state('print', $classes);

        return $this;
    }

    public function state(string $state, string|array|Closure $classes): static
    {
        $this->appendToClassesKey($state, $this->parseClasses($classes));

        return $this;
    }

    public function match(string|Closure $key, array|Closure $options): static
    {
        // evaluate the key if it's a closure
        $key = $this->evaluate($key);

        // evaluate the options if it's a closure
        if ($options instanceof Closure) {
            $options = app()->call($options, [
                'tailor' => $this->tailor,
                'key' => $key,
                'classes' => $this,
            ]);
        }

        // check if the key matches the options
        if (array_key_exists($key, $options)) {
            $value = $options[$key];

            if ($value instanceof Closure) {
                $value = app()->call($value, [
                    'tailor' => $this->tailor,
                    'key' => $key,
                    'classes' => $this,
                ]);
            } elseif (is_array($value)) {
                $this->appendToClassesKey($key, $this->parseClasses($value));
            } elseif (is_string($value)) {
                $this->appendToClassesKey($key, $this->parseClasses($value));
            } else {
                throw new Exception('Invalid value type for key: ' . $key);
            }

            return $this;
        }

        // check if the options array has a default key
        if (array_key_exists('default', $options)) {

            $value = $options['default'];

            if ($value instanceof Closure) {
                $value = app()->call($value, [
                    'tailor' => $this->tailor,
                    'key' => $key,
                    'classes' => $this,
                ]);
            } elseif (is_array($value)) {
                $this->appendToClassesKey($key, $this->parseClasses($value));
            } elseif (is_string($value)) {
                $this->appendToClassesKey($key, $this->parseClasses($value));
            } else {
                throw new Exception('Invalid value type for key: ' . $key);
            }

            return $this;
        }
    }

    public function remove(string|array|Closure $classes, string $key = 'base'): static
    {
        $classes = $this->parseClasses($classes);

        if (isset($this->classes[$key])) {
            $result = trim(preg_replace('/\b' . preg_quote($classes, '/') . '\b(?=\s|$)/', '', $this->classes[$key]));

            $this->classes[$key] = $result;
        }

        return $this;
    }

    public function if(bool|Closure $condition, string|array|Closure $classes): static
    {
        if ($condition instanceof Closure) {
            $condition = app()->call($condition, [
                'tailor' => $this->tailor,
                'classes' => $this,
            ]);
        }

        if ($condition) {
            $this->appendToClassesKey('if', $this->parseClasses($classes));
        }

        return $this;
    }

    public function reset(string $key = null): static
    {
        if ($key === null) {
            $this->classes = [];
        } else {
            unset($this->classes[$key]);
        }

        return $this;
    }


    /**
     * Parse the classes into a string. Handles closures, arrays,
     * and strings. Used to parse the classes for the different
     * pararameter types ultimately to convert them into a string.
     */
    protected function parseClasses(string|array|Closure $classes): string
    {
        if ($classes instanceof Closure) {
            $result = $this->evaluate($classes);

            if (is_callable($result)) {
                return $result($this);
            }

            return (string) $result;
        }

        if (is_array($classes)) {
            return implode(' ', array_unique($classes));
        }

        return (string) $classes;
    }

    /**
     * Append the classes to the given key. If the key does not exist,
     * it will be created and the classes will be appended to it.
     * At this point, the classes should be a string.
     */
    protected function appendToClassesKey(string $key, string $classes): void
    {
        if (! isset($this->classes[$key])) {
            $this->classes[$key] = '';
        }

        // remove any trailing or leading whitespace
        $classes = trim($classes);

        // check if we are using tailwind merge
        if (Tailor::getInstance()->usingTailwindMerge()) {
            $this->classes[$key] = $this->twMerger->merge($this->classes[$key], $classes);
        } else {
            $this->classes[$key] .= ' ' . $classes;
        }
    }

    protected function evaluate(string|array|Closure|BackedEnum|null $value): mixed
    {
        if ($value instanceof Closure) {
            return app()->call($value, [
                'variant' => $this->tailor,
                'classes' => $this->classes,
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
        // first sort the classes by key
        // the order should be base, light, dark
        // and then alphabetically for all other keys
        $classes = collect($this->classes)
            ->sortKeysUsing(function ($a, $b) {
                // sort base first
                if ($a === 'base' && $b !== 'base') {
                    return -1;
                }

                // sort light second
                if ($a === 'light' && $b !== 'light' && $b !== 'base') {
                    return -1;
                }

                // sort dark third
                if ($a === 'dark' && $b !== 'dark' && $b !== 'light' && $b !== 'base') {
                    return -1;
                }

                // sort alphabetically
                return $a <=> $b;
            });

        // now we can dedupe the classes
        $classes = $classes->map(function ($value) {
            return implode(' ', array_unique(explode(' ', $value)));
        });

        // remove any empty classes
        $classes = $classes->filter(function ($value) {
            return ! empty($value);
        });


        $classes = trim($classes->implode(' '));

        // remove any double spaces
        $classes = preg_replace('/\s+/', ' ', $classes);

        // now we can merge the classes together
        return $classes;
    }
}
