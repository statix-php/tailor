<?php

namespace Statix\Tailor;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TailorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('tailor')
            ->hasConfigFile()
            ->hasViews('tailor')
            ->hasViewComponents('tailor');
    }
}
