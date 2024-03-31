<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Hitocean\CrudGenerator\ModelAttributeConfig;

class IntAttr implements ModelAttributeType
{
    public function needsModelCast(): bool
    {
        return false;
    }

    public function modelCast(): string
    {
        throw new \Exception("FloatAttr does not need a model cast.");
    }

    public function fakerFunction(): string
    {
        return 'randomNumber(5)';
    }

    public function migrationFunction(ModelAttributeConfig $config): string
    {
        $base = "integer('{$config->name}')";

        if($config->isNullable){
            return $base . '->nullable()';
        }

        return $base;
    }
}
