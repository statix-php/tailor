<?php

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Statix\Tailor\TailorServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithViews;

    protected function getPackageProviders($app)
    {
        return [
            TailorServiceProvider::class,
        ];
    }
}
