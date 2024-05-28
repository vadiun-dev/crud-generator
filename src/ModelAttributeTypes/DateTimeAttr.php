<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Exception;
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

    public function fakerTestFunction(): string
    {
        return $this->fakerFunction().'->format("Y-m-d H:i:s")';
    }

    public function needsResourceMap(): bool
    {
        return true;
    }

    public function resourceMapProperty(ModelAttributeConfig $config): string
    {
        return $config->name.'->toDateTimeString()';
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

        $base = 'string';

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

    public function needsDataAttribute(): bool
    {
        return false;
    }

    public function dataAttribute(): string
    {
        throw new Exception('DateTimeAttr does not has a data Attribute.');
    }
}
