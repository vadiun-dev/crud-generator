<?php

namespace Hitocean\CrudGenerator\DTOs\Controller;

class MethodInput
{
    public function __construct(
        public string $name,
        public string $type,
        public string $validation,
    ) {
    }
}
