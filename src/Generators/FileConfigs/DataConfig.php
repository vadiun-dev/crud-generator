<?php

namespace Hitocean\CrudGenerator\Generators\FileConfigs;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Illuminate\Support\Collection;

class DataConfig implements FileConfig
{
    public function __construct(
        public string $class_name,
        public string $root_folder,
        public string $root_namespace,
        /** @var Collection<ModelAttributeConfig> */
        public Collection $attributes,
    ) {
    }

    public function fileName(): string
    {
        return $this->className().'.php';
    }

    public function filePath(): string
    {
        return base_path($this->root_folder.'/Data/'.$this->fileName());
    }

    public function className(): string
    {
        return $this->class_name.'Data';
    }

    public function namespace(): string
    {
        return $this->root_namespace.'\\Data';
    }
}
