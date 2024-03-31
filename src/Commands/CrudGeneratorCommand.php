<?php

namespace Hitocean\CrudGenerator\Commands;

use Illuminate\Console\Command;

class CrudGeneratorCommand extends Command
{
    public $signature = 'crud-generator';

    public $description = 'My command';

    public function handle(): int
    {

        $config = \Hitocean\CrudGenerator\CrudGenerator::handle()[0];

        $factory_creator = new \Hitocean\CrudGenerator\Generators\FactoryGenerator();
        $model_creator = new \Hitocean\CrudGenerator\Generators\ModelGenerator();
        $migration_generator = new \Hitocean\CrudGenerator\Generators\MigrationGenerator();
        $migration_generator->create($config);

        $this->info('generado modelo');

        return self::SUCCESS;
    }
}
