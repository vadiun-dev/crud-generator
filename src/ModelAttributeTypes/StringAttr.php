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
        return '$this->faker->word';
    }

    public function fakerTestFunction(): string
    {
        return $this->fakerFunction();
    }

    public function migrationFunction(ModelAttributeConfig $config): string
    {
        $base = "string('{$config->name}')";

        if ($config->isNullable) {
            return $base.'->nullable()';
        }

        return $base;
    }

    public function needsResourceMap(): bool
    {
        return false;
    }

    public function resourceMapProperty(ModelAttributeConfig $config): string
    {
        return $config->name;
    }

    public function dataType(ModelAttributeConfig $config): string
    {
        $base = 'string';
        if ($config->isNullable) {
            return '?'.$base;
        }

        return $base;
    }

    public function resourceType(ModelAttributeConfig $config): string
    {
        $base =  'string';


        if ($config->isNullable) {
            return '?'.$base;
        }

        return $base;
    }

    public function needsImport(): bool
    {
        return false;
    }

    public function importPath(): string
    {
        throw new \Exception('StringAttr does not need an import path.');
    }

    public function needsDataAttribute(): bool
    {
        return false;
    }

    public function dataAttribute(): string
    {
        throw new Exception('StringAttr does not has a data Attribute.');
    }
}
