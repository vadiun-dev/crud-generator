<?php

namespace Hitocean\CrudGenerator\Commands;

use DirectoryIterator;
use Hitocean\CrudGenerator\FileGenerators\Model\FactoryGenerator;
use Hitocean\CrudGenerator\FileGenerators\Model\FileConfigs\FactoryConfig;
use Hitocean\CrudGenerator\FileGenerators\Model\MigrationGenerator;
use Hitocean\CrudGenerator\FileGenerators\Model\ModelGenerator;
use Hitocean\CrudGenerator\ModelConfigFactory;
use Illuminate\Console\Command;

class ModelGeneratorCommand extends Command
{
    public $signature = 'make:hit-model';

    public $description = 'My command';

    public function handle(): int
    {
        $factory_creator = new FactoryGenerator();
        $model_creator = new ModelGenerator();
        $migration_generator = new MigrationGenerator();

        $model_configs = [];

        $iterator = new DirectoryIterator(base_path('generators/models'));
        foreach ($iterator as $json_conf) {
            if (! $json_conf->isDot()) {
                $configData = json_decode(
                    file_get_contents($json_conf->getPath().'/'.$json_conf->getFilename()),
                    true
                );
                $model_configs[] = ModelConfigFactory::makeConfig($configData);
            }
        }

        foreach ($model_configs as $config) {

            $migration_generator->create($config);
            $model_creator->create($config);
            $factory_creator->create(
                new FactoryConfig($config->attributes, $config->root_namespace.'\\Models\\'.$config->className())
            );
        }

        $this->info('generado modelo');

        return self::SUCCESS;
    }
}
