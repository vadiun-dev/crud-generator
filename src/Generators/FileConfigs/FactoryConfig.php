<?php

namespace Hitocean\CrudGenerator\Generators\FileConfigs;

use Illuminate\Support\Collection;

class FactoryConfig implements FileConfig
{
    public function __construct(
        /** @var Collection<ModelAttributeConfig> */
        public Collection $attributes,
        public string $model_import
    ) {
    }

    public function fileName(): string
    {
        return $this->modelClassName().'Factory.php';
    }

    public function modelClassName(): string
    {
        return class_basename($this->model_import);
    }

    public function filePath(): string
    {
        return database_path('factories/'.$this->fileName());
    }

    public function className(): string
    {
        return $this->modelClassName().'Factory';
    }

    public function namespace(): string
    {
        return 'Database\\Factories';
    }
}
{

}
