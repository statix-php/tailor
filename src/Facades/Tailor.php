<?php

namespace Statix\Tailor\Facades;

use Illuminate\Support\Facades\Facade;
use Statix\Tailor\Tailor as TailorManager;

class Tailor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TailorManager::class;
    }
}
