<?php

namespace Hitocean\CrudGenerator;

use Hitocean\CrudGenerator\ModelAttributeTypes\ModelAttributeType;

class ModelAttributeConfig
{
    public function __construct(
        public string $name,
        public ModelAttributeType $type,
        public bool $isNullable = false,
    ){}
}
