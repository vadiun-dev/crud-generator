<?php

namespace Hitocean\CrudGenerator\Commands;

use DirectoryIterator;
use Hitocean\CrudGenerator\ControllerConfigFactory;
use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\Generators\ControllerGenerator;
use Hitocean\CrudGenerator\Generators\ControllerTestGenerator;
use Hitocean\CrudGenerator\Generators\DataGenerator;
use Hitocean\CrudGenerator\Generators\FactoryGenerator;
use Hitocean\CrudGenerator\Generators\FileConfigs\ControllerTestConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\DataConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\FactoryConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ResourceConfig;
use Hitocean\CrudGenerator\Generators\MigrationGenerator;
use Hitocean\CrudGenerator\Generators\ModelGenerator;
use Hitocean\CrudGenerator\Generators\ResourceGenerator;
use Hitocean\CrudGenerator\ModelAttributeTypes\IdentifierAttr;
use Hitocean\CrudGenerator\ModelConfigFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CrudGeneratorCommand extends Command
{
    public $signature = 'crud-generator';

    public $description = 'My command';

    public function handle(): int
    {
        $factory_creator = new FactoryGenerator();
        $model_creator = new ModelGenerator();
        $migration_generator = new MigrationGenerator();
        $controller_creator = new ControllerGenerator();
        $data_generator = new DataGenerator();
        $resource_generator = new ResourceGenerator();
        $test_generator = new ControllerTestGenerator();

        $model_configs = [];
        $controller_configs = [];

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

        $iterator = new DirectoryIterator(base_path('generators/controllers'));
        foreach ($iterator as $json_conf) {
            if (! $json_conf->isDot()) {
                $configData = json_decode(
                    file_get_contents($json_conf->getPath().'/'.$json_conf->getFilename()),
                    true
                );
                $controller_configs[] = ControllerConfigFactory::makeConfig($configData);
            }
        }

        foreach ($model_configs as $config) {

            $migration_generator->create($config);
            $model_creator->create($config);
            $factory_creator->create(
                new FactoryConfig($config->attributes, $config->root_namespace.'\\Models\\'.$config->className())
            );
        }

        foreach ($controller_configs as $config) {

            $data_generator->create(
                new DataConfig(
                    'Store'.$config->modelClassName(),
                    $config->root_folder,
                    $config->root_namespace,
                    $config->model_attributes
                )
            );

            $route_parameter = Str::lower(Str::singular($config->modelClassName()));
            $data_generator->create(
                new DataConfig(
                    'Update'.$config->modelClassName(),
                    $config->root_folder,
                    $config->root_namespace,
                    $config->model_attributes->values()->add(new ModelAttributeConfig('id', new IdentifierAttr($route_parameter), false))
                )
            );

            $resource_generator->create(
                new ResourceConfig(
                    $config->modelClassName(),
                    $config->root_folder,
                    $config->root_namespace,
                    $config->model_attributes->values()->add(new ModelAttributeConfig('id', new IdentifierAttr('banner'), false)),
                    $config->model_import
                )
            );
            $resource_generator->create(
                new ResourceConfig(
                    'Detailed'.$config->modelClassName(),
                    $config->root_folder,
                    $config->root_namespace,
                    $config->model_attributes->values()->add(new ModelAttributeConfig('id', new IdentifierAttr('banner'), false)),
                    $config->model_import
                )
            );

            $controller_creator->create($config);

            $test_generator->create(
                new ControllerTestConfig(
                    $config->root_namespace.'\\Controllers\\'.$config->className(),
                    $config->model_import,
                    $config->model_attributes->values()->add(new ModelAttributeConfig('id', new IdentifierAttr('banner'), false)),
                    'tests',
                    'Tests',
                    collect([])
                )
            );
        }

        $this->info('generado modelo');

        return self::SUCCESS;
    }
}
