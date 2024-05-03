<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;

class StringAttr implements ModelAttributeType
{
    public function needsModelCast(): bool
    {
        return false;
    }

    public function modelCast(): string
    {
        throw new \Exception('StringAttr does not need a model cast.');
    }

    public function fakerFunction(): string
    {
        return 'word';
    }

    public function migrationFunction(ModelAttributeConfig $config): string
    {
        $base = "string('{$config->name}')";

        if ($config->isNullable) {
            return $base.'->nullable()';
        }

        return $base;
    }
}
