<?php

namespace Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes;

use Exception;
use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;

class IntAttr implements ModelAttributeType
{
    public function needsModelCast(): bool
    {
        return false;
    }

    public function modelCast(): string
    {
        throw new Exception('IntAttr does not need a model cast.');
    }

    public function fakerFunction(): string
    {
        return '$this->faker->randomNumber(5)';
    }

    public function fakerTestFunction(): string
    {
        return $this->fakerFunction();
    }

    public function needsResourceMap(): bool
    {
        return false;
    }

    public function resourceMapProperty(ModelAttributeConfig $config): string
    {
        return $config->name;
    }

    public function migrationFunction(ModelAttributeConfig $config): string
    {
        $base = "integer('{$config->name}')";

        if ($config->isNullable) {
            return $base.'->nullable()';
        }

        return $base;
    }

    public function dataType(ModelAttributeConfig $config): string
    {
        $base = 'int';
        if ($config->isNullable) {
            return '?'.$base;
        }

        return $base;
    }

    public function resourceType(ModelAttributeConfig $config): string
    {
        $base = 'int';

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
        throw new \Exception('IntAttr does not need an import path.');
    }

    public function needsDataAttribute(): bool
    {
        return false;
    }

    public function dataAttribute(): string
    {
        throw new Exception('IntAttr does not has a data Attribute.');
    }
}
