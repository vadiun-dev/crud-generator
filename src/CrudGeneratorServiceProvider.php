<?php

namespace Hitocean\CrudGenerator;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Hitocean\CrudGenerator\Commands\CrudGeneratorCommand;

class CrudGeneratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('crud-generator')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_crud-generator_table')
            ->hasCommand(CrudGeneratorCommand::class);
    }
}
