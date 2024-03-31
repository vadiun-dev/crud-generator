<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Hitocean\CrudGenerator\ModelAttributeConfig;

class DateTimeAttr implements ModelAttributeType
{

    public function needsModelCast(): bool
    {
        return true;
    }

    public function modelCast(): string
    {
        return 'datetime';
    }

    public function fakerFunction(): string
    {
        return 'dateTime';
    }

    public function migrationFunction(ModelAttributeConfig $config): string
    {
        $base = "dateTime('{$config->name}')";
        if ($config->isNullable) {
            return $base . '->nullable()';
        }

        return $base;
    }
}
