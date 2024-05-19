<?php

namespace Hitocean\CrudGenerator\Generators\FileConfigs;

use Illuminate\Support\Collection;

class ControllerTestConfig implements FileConfig
{
    public function __construct(
        public string $controller_import,
        public string $model_import,
        /** @var Collection<ModelAttributeConfig> */
        public Collection $model_attributes,
        public string $root_folder,
        public string $root_namespace,
        /** @var Collection<ControllerMethodConfig> */
        public Collection $methods
    ) {
    }

    public function fileName(): string
    {
        return $this->className().'.php';
    }

    public function filePath(): string
    {
        return base_path($this->root_folder.'/Controllers/'.$this->fileName());
    }

    public function className(): string
    {
        return $this->controllerClassName().'Test';
    }

    public function modelClassName(): string
    {
        return class_basename($this->model_import);
    }

    public function controllerClassName(): string
    {
        return class_basename($this->controller_import);
    }

    public function namespace(): string
    {
        return $this->root_namespace.'\\Controllers';
    }


}
