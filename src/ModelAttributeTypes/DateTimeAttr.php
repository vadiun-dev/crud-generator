<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;

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
        return '$this->faker->dateTime';
    }

    public function migrationFunction(ModelAttributeConfig $config): string
    {
        $base = "dateTime('{$config->name}')";
        if ($config->isNullable) {
            return $base.'->nullable()';
        }

        return $base;
    }

    public function dataType(ModelAttributeConfig $config): string
    {
        $base = 'Carbon';

        if ($config->isNullable) {
            return '?'.$base;
        }

        return $base;
    }

    public function resourceType(ModelAttributeConfig $config): string
    {
        $base = 'Carbon';

        if ($config->isNullable) {
            return '?'.$base;
        }

        return $base;
    }

    public function needsImport(): bool
    {
        return true;
    }

    public function importPath(): string
    {
        return 'Carbon\Carbon';
    }
}
