<?php

namespace Hitocean\CrudGenerator\DTOs\Controller;

use Illuminate\Support\Collection;

class ControllerMethodConfig
{
    public function __construct(
        public MethodName $name,
        public string $method_type,
        public string $route_method,
        /** @var Collection<MethodInput> */
        public Collection $inputs,
        public Collection $outputs,
        public string $response_type,
    ) {
    }
}
