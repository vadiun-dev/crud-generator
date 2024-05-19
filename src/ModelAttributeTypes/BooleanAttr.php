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
        return '$this->faker->boolean';
    }

    public function migrationFunction(ModelAttributeConfig $config): string
    {
        $base = "boolean('{$config->name}')";

        if ($config->isNullable) {
            return $base.'->nullable()';
        }

        return $base;
    }

    public function dataType(ModelAttributeConfig $config): string
    {
        return 'bool';
    }

    public function resourceType(ModelAttributeConfig $config): string
    {
        $base = 'bool';

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
        throw new \Exception('BooleanAttr does not need an import path.');
    }
}
