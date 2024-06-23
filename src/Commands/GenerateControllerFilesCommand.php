<?php

namespace Hitocean\CrudGenerator\Commands;

use DirectoryIterator;
use Hitocean\CrudGenerator\ControllerConfigFactory;
use Hitocean\CrudGenerator\FileGenerators\Controller\DataGenerator;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\DataConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ModelControllerConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ResourceConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelController\ModelControllerGenerator;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest\ModelControllerTestGenerator;
use Hitocean\CrudGenerator\FileGenerators\Controller\ResourceGenerator;
use Illuminate\Console\Command;

use function class_basename;

class GenerateControllerFilesCommand extends Command
{
    public $signature = 'make:hit-controller';

    public $description = 'My command';

    public function handle(): int
    {
        $controller_creator = new ModelControllerGenerator();
        $data_generator = new DataGenerator();
        $resource_generator = new ResourceGenerator();
        $test_generator = new ModelControllerTestGenerator();

        $controller_configs = [];

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

        /** @var ModelControllerConfig $config */
        foreach ($controller_configs as $config) {

            foreach ($config->methods as $method) {
                if ($method->data_class_import) {
                    $data_generator->create(
                        new DataConfig(
                            class_basename($method->data_class_import),
                            $method->dataRootPath(),
                            $method->dataRootNamespace(),
                            $method->inputs
                        )
                    );
                }

                if ($method->resource_class_import) {
                    $resource_generator->create(
                        new ResourceConfig(
                            $method->resourceClassName(),
                            $method->resourceRootPath(),
                            $method->resourceRootNamespace(),
                            $method->outputs,
                            $config->model_import
                        )
                    );
                }
            }

            $controller_creator->create($config);

            $test_generator->create($config);
        }

        $this->info('generado modelo');

        return self::SUCCESS;
    }
}
