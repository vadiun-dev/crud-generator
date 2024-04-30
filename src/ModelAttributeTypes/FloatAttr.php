<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Exception;
use Hitocean\CrudGenerator\ModelAttributeConfig;

class FloatAttr implements ModelAttributeType
{
    public function needsModelCast(): bool
    {
        return false;
    }

    public function modelCast(): string
    {
        throw new Exception('FloatAttr does not need a model cast.');
    }

    public function fakerFunction(): string
    {
        return 'randomFloat(2, 0, 100)';
    }

    public function migrationFunction(ModelAttributeConfig $config): string
    {
        $base = "float('{$config->name}')";

        if ($config->isNullable) {
            return $base.'->nullable()';
        }

        return $base;
    }
}
