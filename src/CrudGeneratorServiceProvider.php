<?php

namespace Hitocean\CrudGenerator;

use Hitocean\CrudGenerator\Commands\CreateModelJsonController;
use Hitocean\CrudGenerator\Commands\CreateJsonModel;
use Hitocean\CrudGenerator\Commands\ModelGeneratorCommand;
use Hitocean\CrudGenerator\Commands\GenerateControllerFilesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasCommand(ModelGeneratorCommand::class)
            ->hasCommand(CreateModelJsonController::class)
            ->hasCommand(GenerateControllerFilesCommand::class)
        ->hasCommand(CreateJsonModel::class);
    }
}
