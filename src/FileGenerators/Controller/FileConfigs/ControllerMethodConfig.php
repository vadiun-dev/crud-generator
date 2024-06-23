<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Illuminate\Support\Collection;

class ControllerMethodConfig
{
    public function __construct(
        public string $name,
        public string $route_method,
        /** @var Collection<ModelAttributeConfig> */
        public Collection $inputs,
        public ?string $data_class_path,
        public ?string $data_class_import,
        public ?string $resource_class_import,
        public ?string $resource_class_path,
        /** @var Collection<ModelAttributeConfig> */
        public Collection $outputs,
    ) {}

    public function dataClassName(): string
    {
        return class_basename($this->data_class_import);
    }

    public function resourceClassName(): string
    {
        return class_basename($this->resource_class_import);
    }

    public function dataRootPath(): string
    {
        $parts = explode('/', $this->data_class_path);
        array_pop($parts);

        return implode('/', $parts);
    }

    public function resourceRootPath(): string
    {
        $parts = explode('/', $this->resource_class_path);
        array_pop($parts);

        return implode('/', $parts);
    }

    public function resourceRootNamespace(): string
    {
        $parts = explode('\\', $this->resource_class_import);
        array_pop($parts);

        return implode('\\', $parts);
    }

    public function dataRootNamespace(): string
    {
        $parts = explode('\\', $this->data_class_import);
        array_pop($parts);

        return implode('\\', $parts);
    }
}
