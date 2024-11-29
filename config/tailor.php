<?php

return [
    'features' => [

        /**
         * Enable or disable the Tailwind CSS merge feature.
         *
         * This feature requires you to install the `https://github.com/gehrisandro/tailwind-merge-php` package.
         */
        'tailwind_merge_enabled' => false,

        /**
         * This option controls whether the key name is included in the class string. This can be useful for debugging.
         *
         * For example:
         * class="(base) text-white (light) bg-gray-200 (dark) dark:bg-gray-900"
         */
        'include_key_name_in_class_string' => false,
    ],
];
