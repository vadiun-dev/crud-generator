<?php

namespace Hitocean\CrudGenerator\DTOs\Controller;

use Hitocean\CrudGenerator\DTOs\Model\ModelConfig;
use Illuminate\Support\Collection;

class ControllerConfig
{
    public function __construct(
        public string $controller_name,
        public ModelConfig $model_config,
        public string $folder,
        /** @var Collection<ControllerMethodConfig> */
        public Collection $methods,
        public bool $has_abm
    ) {
    }
}
