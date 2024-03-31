<?php

namespace Hitocean\CrudGenerator\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\App;
use Orchestra\Testbench\TestCase as Orchestra;
use Hitocean\CrudGenerator\CrudGeneratorServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Hitocean\\CrudGenerator\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
        App::setBasePath(__DIR__);
    }

    protected function getPackageProviders($app)
    {
        return [
            CrudGeneratorServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_crud-generator_table.php.stub';
        $migration->up();
        */
    }
}
