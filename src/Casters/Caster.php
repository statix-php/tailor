<?php

namespace Statix\Tailor\Casters;

interface Caster
{
    public function toString(array $value): string;

    public function toHtml(array $value): string;
}
