<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\ModelController;

use Hitocean\CrudGenerator\DTOs\Controller\ModelControllerMethodConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerMethodConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ModelControllerConfig;
use Hitocean\CrudGenerator\FileGenerators\FileGenerator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;

class ModelControllerGenerator extends FileGenerator
{
    /**
     * Genera el archivo del controlador basado en la configuración proporcionada.
     *
     * @param ModelControllerConfig $config
     * @return void
     */
    public function create($config): void
    {
        $file = new PhpFile();

        $namespace = $file->addNamespace($config->root_namespace)
                          ->addUse($config->model_import);

        // Importar los data y resource de cada método en config
        foreach ($config->methods as $methodConfig) {
            $this->importDependencies($namespace, $methodConfig);
        }

        $class = $namespace->addClass($config->className());

        // Definir los métodos del controlador basados en los métodos del config
        foreach ($config->methods as $methodConfig) {
            $this->defineMethod($config, $methodConfig, $class);
        }

        $this->createFile($config->filePath(), $file);
    }

    private function importDependencies($namespace, ControllerMethodConfig $methodConfig): void
    {
        if ($methodConfig->data_class_import) {
            $namespace->addUse($methodConfig->data_class_import);
        }
        if ($methodConfig->resource_class_import) {
            $namespace->addUse($methodConfig->resource_class_import);
        }
    }


    private function defineMethod(ModelControllerConfig $config, ControllerMethodConfig $methodConfig, ClassType $class): void
    {

        switch (strtolower($methodConfig->route_method)) {
            case 'post':
                ModelControllerStoreMethod::create($methodConfig->inputs, $methodConfig->name, $config->modelClassName(), $class, $methodConfig->data_class_import);
                break;
            case 'put':
                ModelControllerUpdateMethod::create($methodConfig->inputs, $methodConfig->name, $config->modelClassName(), $class, $methodConfig->data_class_import);
                break;
            case 'delete':
                ModelControllerDeleteMethod::create($methodConfig->inputs, $methodConfig->name, $config->modelClassName(), $class);
                break;
            case 'get':
                if ($methodConfig->name === 'index') {
                    ModelControllerIndexMethod::create($methodConfig->outputs, $methodConfig->name, $config->modelClassName(), $class, $methodConfig->resource_class_import);
                } elseif ($methodConfig->name === 'show') {
                    ModelControllerShowMethod::create($methodConfig->outputs, $methodConfig->name, $config->modelClassName(), $class, $methodConfig->resource_class_import);
                }
                break;
        }

    }


}
