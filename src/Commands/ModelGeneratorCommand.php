<?php

namespace Hitocean\CrudGenerator\Commands;

use DirectoryIterator;
use Hitocean\CrudGenerator\ControllerConfigFactory;
use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\DataGenerator;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerTestConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\DataConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ResourceConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelController\ModelControllerGenerator;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest\ModelControllerTestGenerator;
use Hitocean\CrudGenerator\FileGenerators\Controller\ResourceGenerator;
use Hitocean\CrudGenerator\FileGenerators\Model\FactoryGenerator;
use Hitocean\CrudGenerator\FileGenerators\Model\FileConfigs\FactoryConfig;
use Hitocean\CrudGenerator\FileGenerators\Model\MigrationGenerator;
use Hitocean\CrudGenerator\FileGenerators\Model\ModelGenerator;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\IdentifierAttr;
use Hitocean\CrudGenerator\ModelConfigFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ModelGeneratorCommand extends Command
{
    public $signature = 'make:hit-model';

    public $description = 'My command';

    public function handle(): int
    {
        $factory_creator     = new FactoryGenerator();
        $model_creator       = new ModelGenerator();
        $migration_generator = new MigrationGenerator();

        $model_configs      = [];

        $iterator = new DirectoryIterator(base_path('generators/models'));
        foreach ($iterator as $json_conf) {
            if (!$json_conf->isDot()) {
                $configData      = json_decode(
                    file_get_contents($json_conf->getPath() . '/' . $json_conf->getFilename()),
                    true
                );
                $model_configs[] = ModelConfigFactory::makeConfig($configData);
            }
        }


        foreach ($model_configs as $config) {

            $migration_generator->create($config);
            $model_creator->create($config);
            $factory_creator->create(
                new FactoryConfig($config->attributes, $config->root_namespace . '\\Models\\' . $config->className())
            );
        }

        $this->info('generado modelo');

        return self::SUCCESS;
    }
}
