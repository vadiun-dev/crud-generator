<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest;

use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerMethodConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ModelControllerConfig;
use Hitocean\CrudGenerator\FileGenerators\FileGenerator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;

class ModelControllerTestGenerator extends FileGenerator
{
    /**
     * Genera el archivo de prueba del controlador basado en la configuración proporcionada.
     *
     * @param  ModelControllerConfig  $config
     */
    public function create($config): void
    {
        $file = new PhpFile();

        $test_case_import = 'Tests\\TestCase';
        $namespace = $file->addNamespace($config->testNamespace())
            ->addUse($config->model_import)
            ->addUse($test_case_import)
            ->addUse($config->root_namespace.'\\'.$config->controller_name);

        $class = $namespace->addClass($config->testClassName())->setExtends($test_case_import);

        foreach ($config->methods as $methodConfig) {
            $this->defineMethod($methodConfig, $config, $class);
        }

        $this->createFile($config->test_path.'.php', $file);
    }

    private function defineMethod(
        ControllerMethodConfig $methodConfig,
        ModelControllerConfig $config,
        ClassType $class
    ): void {
        switch (strtolower($methodConfig->route_method)) {
            case 'post':
                ModelControllerTestStoreMethod::create(
                    $methodConfig->inputs,
                    $methodConfig->name,
                    $config->modelClassName(),
                    $config->controller_name,
                    $class
                );
                break;
            case 'put':
                ModelControllerTestUpdateMethod::create(
                    $methodConfig->inputs,
                    $methodConfig->name,
                    $config->modelClassName(),
                    $config->controller_name,
                    $class
                );
                break;
            case 'delete':
                ModelControllerTestDeleteMethod::create(
                    $methodConfig->inputs,
                    $methodConfig->name,
                    $config->modelClassName(),
                    $config->controller_name,
                    $class
                );
                break;
            case 'get':
                if ($methodConfig->name === 'index') {
                    ModelControllerTestIndexMethod::create(
                        $methodConfig->outputs,
                        $methodConfig->name,
                        $config->modelClassName(),
                        $config->controller_name,
                        $class
                    );
                } elseif ($methodConfig->name === 'show') {
                    ModelControllerTestShowMethod::create(
                        $methodConfig->outputs,
                        $methodConfig->name,
                        $config->modelClassName(),
                        $config->controller_name,
                        $class
                    );
                }
                break;
        }

    }
}
