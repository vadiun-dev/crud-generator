<?php

namespace Hitocean\CrudGenerator\Generators\FileConfigs;

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
        public bool $has_abm
    ) {
    }

    public function fileName(): string
    {
        return $this->modelName.'.php';
    }

    public function filePath(): string
    {
        return base_path($this->root_folder.'/Models/'.$this->fileName());
    }

    public function className(): string
    {
        return $this->modelName;
    }

    public function namespace(): string
    {
        return $this->root_namespace.'\\Models';
    }

    public function import(): string
    {
        return $this->namespace().'\\'.$this->modelName;
    }


}
