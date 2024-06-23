<?php

namespace Hitocean\CrudGenerator\DTOs\Model;

use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\ModelAttributeType;

class ModelAttributeConfig
{
    public function __construct(
        public string $name,
        public ModelAttributeType $type,
        public bool $isNullable = false,
    ) {
    }
}
