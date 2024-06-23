<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs;

use Illuminate\Support\Collection;

class ControllerConfig
{
    public function __construct(
        public string $controller_name,
        public string $root_folder,
        public string $root_namespace,
        /** @var Collection<ControllerMethodConfig> */
        public Collection $methods,
        public string $test_folder
    ) {}

    public function filePath(): string
    {
        return base_path($this->root_folder.'/'.$this->controller_name.'.php');
    }
}
