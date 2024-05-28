<?php

namespace Hitocean\CrudGenerator\Generators\FileConfigs;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Illuminate\Support\Collection;

class ControllerConfig implements FileConfig
{
    public function __construct(
        public string $controller_name,
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
        return $this->controller_name.'.php';
    }

    public function filePath(): string
    {
        return base_path($this->root_folder.'/Controllers/'.$this->fileName());
    }

    public function modelClassName(): string
    {
        return class_basename($this->model_import);
    }

    public function className(): string
    {
        return $this->controller_name;
    }

    public function namespace(): string
    {
        return $this->root_namespace.'\\Controllers';
    }

    public function storeDataImport(): string
    {
        return $this->root_namespace.'\\Data\\Store'.$this->modelClassName().'Data';
    }

    public function updateDataImport(): string
    {
        return $this->root_namespace.'\\Data\\Update'.$this->modelClassName().'Data';
    }

    public function indexResourceImport(): string
    {
        return $this->root_namespace.'\\Resources\\'.$this->indexResourceClassName();
    }

    public function showResourceImport(): string
    {
        return $this->root_namespace.'\\Resources\\'.$this->showResourceClassName();
    }

    public function indexResourceClassName(): string
    {
        return $this->modelClassName().'Resource';
    }

    public function showResourceClassName(): string
    {
        return 'Detailed'.$this->modelClassName().'Resource';
    }
}
