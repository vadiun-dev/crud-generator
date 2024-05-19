<?php

namespace Hitocean\CrudGenerator\Generators;

use Hitocean\CrudGenerator\Generators\FileConfigs\ControllerConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ModelConfig;
use Illuminate\Support\Collection;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;

class ControllerGenerator extends FileGenerator
{
    /**
     * @param ControllerConfig $config
     */
    public function create($config): void
    {
        $file = new PhpFile();

        $namespace = $file->addNamespace($config->namespace())
            ->addUse($config->model_import)
            ->addUse($config->updateDataImport())
            ->addUse($config->storeDataImport())
            ->addUse($config->indexResourceImport())
            ->addUse($config->showResourceImport());

        $class = $namespace->addClass($config->className());

        $this->storeMethod($config, $class);
        $this->updateMethod($config, $class);
        $this->destroyMethod($config, $class);
        $this->indexMethod($config, $class);
        $this->showMethod($config, $class);

        $this->createFile($config->filePath(), $file);

    }

    public function storeMethod(ControllerConfig $config, ClassType $class): void
    {
        $method = $class->addMethod('store')
            ->setVisibility('public')
            ->addBody("\$model = {$config->modelClassName()}::create([");

        foreach ($config->model_attributes as $attr) {
            $method->addBody("'{$attr->name}' => \$data->{$attr->name},");
        }

        $method->addBody(']);')
            ->setReturnType('void')
            ->addParameter('data')
            ->setType($config->storeDataImport());

    }

    public function updateMethod(ControllerConfig $config, ClassType $class): void
    {
        $method = $class->addMethod('update')
            ->setVisibility('public')
            ->addBody("\$model = {$config->modelClassName()}::findOrFail(\$data->id);")
            ->addBody('$model->update([');

        foreach ($config->model_attributes as $attr) {
            $method->addBody("'{$attr->name}' => \$data->{$attr->name},");
        }

        $method->addBody(']);')
            ->setReturnType('void')
            ->addParameter('data')
            ->setType($config->updateDataImport());

    }

    public function destroyMethod(ControllerConfig $config, ClassType $class): void
    {
        $class->addMethod('destroy')
            ->setVisibility('public')
            ->addBody("{$config->modelClassName()}::destroy(\$id);")
            ->setReturnType('void')
            ->addParameter('id')
            ->setType('int');

    }

    public function indexMethod(ControllerConfig $config, ClassType $class): void
    {
        $class->addMethod('index')
            ->setVisibility('public')
            ->addBody("return {$config->indexResourceClassName()}::collection({$config->modelClassName()}::all());");

    }

    public function showMethod(ControllerConfig $config, ClassType $class): void
    {
        $class->addMethod('show')
            ->setVisibility('public')
            ->addBody("return {$config->showResourceClassName()}::from({$config->modelClassName()}::findOrFail(\$id));")
            ->addParameter('id')
            ->setType('int');

    }


}
