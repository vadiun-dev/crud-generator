<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;

class BooleanAttr implements ModelAttributeType
{
    public function needsModelCast(): bool
    {
        return true;
    }

    public function modelCast(): string
    {
        return 'bool';
    }

    public function fakerFunction(): string
    {
        return 'boolean';
    }

    public function migrationFunction(ModelAttributeConfig $config): string
    {
        $base = "boolean('{$config->name}')";

        if ($config->isNullable) {
            return $base.'->nullable()';
        }

        return $base;
    }
}
