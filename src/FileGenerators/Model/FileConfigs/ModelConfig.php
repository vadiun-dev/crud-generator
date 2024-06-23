<?php

namespace Hitocean\CrudGenerator\FileGenerators\Model\FileConfigs;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\FileConfig;
use Illuminate\Support\Collection;

class ModelConfig implements FileConfig
{
    public function __construct(
        public string $modelName,
        public string $root_folder,
        public string $root_namespace,
        /** @var Collection<ModelAttributeConfig> */
        public Collection $attributes,
        public string $tableName,
    ) {}

    public function fileName(): string
    {
        return $this->modelName.'.php';
    }

    public function filePath(): string
    {
        return base_path($this->root_folder.'/'.$this->fileName());
    }

    public function className(): string
    {
        return $this->modelName;
    }

    public function namespace(): string
    {
        return $this->root_namespace;
    }

    public function import(): string
    {
        return $this->namespace().'\\'.$this->modelName;
    }
}
