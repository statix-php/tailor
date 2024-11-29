<?php

namespace Statix\Tailor\Casters;

class ClassStringCaster implements Caster
{
    public function toString(array $values): string
    {
        /* okay so we need to:
         1) sort the classes by key to keep 'base' at the start
         2) then we want light to come before dark
         3) then we want the rest to be alphabetical
        */
        $classes = collect($values)->sortKeysUsing(function ($a, $b) {
            if ($a === 'base') {
                return -1;
            }

            if ($b === 'base') {
                return 1;
            }

            // if a contains light and b contains dark, then a should come before b
            if (str_contains($a, 'light') && str_contains($b, 'dark')) {
                return -1;
            }

            // if a contains dark and b contains light, then a should come after b
            if (str_contains($a, 'dark') && str_contains($b, 'light')) {
                return 1;
            }

            // sort alphabetically
            return $a <=> $b;
        });

        // so now we have the keys sorted, we need to sort the values
        $classes = $classes->map(function ($value) {
            if (is_array($value)) {
                return collect($value)->sort()->values()->all();
            }

            return $value;
        });

        // so now are data is sorted by key, and the values are sorted

        // so we can deduplicate the values in each key
        $classes = $classes->map(function ($value) {
            if (is_array($value)) {
                return collect($value)->unique()->filter()->values()->all();
            }

            return $value;
        });

        // so now we have deduplicated the values in each key
        // let's implode the values into a string
        $classes = $classes->map(function ($value) {
            if (is_array($value)) {
                return implode(' ', $value);
            }

            return $value;
        });

        // so now we have imploded the values into a string
        // let's implode the keys and values into a string
        $values = trim($classes->implode(' '));

        // remove any double spaces
        $values = preg_replace('/\s+/', ' ', $values);

        /**
         * Lastly, we need to remove any double spaces
         * and replace them with a single space
         */

        return (string) $values;
    }

    public function toHtml($value): string
    {
        return $value;
    }
}
