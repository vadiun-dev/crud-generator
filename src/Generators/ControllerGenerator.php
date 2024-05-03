<?php

namespace Hitocean\CrudGenerator\Generators;

use Hitocean\CrudGenerator\DTOs\Controller\ControllerConfig;
use Hitocean\CrudGenerator\DTOs\Model\ModelConfig;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;

class ControllerGenerator extends FileGenerator
{
    public function create(ModelConfig|ControllerConfig $config): void
    {
        $file = new PhpFile();

        $namespace = $file->addNamespace($config->folder.'\\Controllers')
            ->addUse($config->modelName);

        $class = $namespace->addClass($config->controller_name);

        $this->storeMethod($class, $config->model_config);
        $this->updateMethod($class, $config->model_config);
        $this->destroyMethod($class, $config->model_config);
        $this->indexMethod($class, $config->model_config);
        $this->showMethod($class, $config->model_config);

        $this->createFile(base_path($config->folder.'/Controllers/'.$config->controller_name.'.php'), $file);

    }

    public function storeMethod(ClassType $class, ModelConfig $model_config): void
    {
        $method = $class->addMethod('store')
            ->setVisibility('public')
            ->addBody("\$model = $model_config->modelName::create([");

        foreach ($model_config->attributes as $attr) {
            $method->addBody("'{$attr->name}' => \$data->name,");
        }

        $method->addBody(']);')
            ->setReturnType('void');

    }

    public function updateMethod(ClassType $class, ModelConfig $model_config): void
    {
        $method = $class->addMethod('update')
            ->setVisibility('public')
            ->addBody("\$model = $model_config->modelName::findOrFail(\$data->id);")
            ->addBody('$model->update([');

        foreach ($model_config->attributes as $attr) {
            $method->addBody("'{$attr->name}' => \$data->name,");
        }

        $method->addBody(']);')
            ->setReturnType('void');

    }

    public function destroyMethod(ClassType $class, ModelConfig $model_config): void
    {
        $method = $class->addMethod('destroy')
            ->setVisibility('public')
            ->addBody("$model_config->modelName::destroy(\$id);")
            ->setReturnType('void')
            ->addParameter('id')
            ->setType('int');

    }

    public function indexMethod(ClassType $class, ModelConfig $model_config): void
    {
        $method = $class->addMethod('index')
            ->setVisibility('public')
            ->addBody("return $model_config->modelName::all();");

    }

    public function showMethod(ClassType $class, ModelConfig $model_config): void
    {
        $method = $class->addMethod('show')
            ->setVisibility('public')
            ->addBody("return $model_config->modelName::findOrFail(\$id);")
            ->addParameter('id')
            ->setType('int');

    }
}
