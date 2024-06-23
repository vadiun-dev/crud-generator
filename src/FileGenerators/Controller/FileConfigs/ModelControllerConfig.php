<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs;

use Illuminate\Support\Collection;

use function explode;
use function implode;

class ModelControllerConfig
{
    public function __construct(
        public string $controller_name,
        public string $model_import,
        public string $root_folder,
        public string $root_namespace,
        public string $test_path,
        /** @var Collection<ControllerMethodConfig> */
        public Collection $methods,
    ) {}

    public function fileName(): string
    {
        return $this->controller_name.'.php';
    }

    public function filePath(): string
    {
        return base_path($this->root_folder.'/'.$this->fileName());
    }

    public function modelClassName(): string
    {
        return class_basename($this->model_import);
    }

    public function className(): string
    {
        return $this->controller_name;
    }

    public function testClassName(): string
    {
        return class_basename($this->test_path);
    }

    public function testNamespace(): string
    {
        $tests = explode('/', $this->test_path);
        $tests[0] = 'Tests';
        array_pop($tests);

        return implode('\\', $tests);

    }
}
