<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Exception;
use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Spatie\LaravelData\Attributes\FromRouteParameter;

class IdentifierAttr implements ModelAttributeType
{

    public function __construct(
        private string $route_parameter
    ){}
    public function needsModelCast(): bool
    {
        return false;
    }

    public function modelCast(): string
    {
        throw new Exception('IdentifierAttr does not need a model cast.');
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
        if($config->isNullable){
            return '?'.$base;
        }

        return $base;
    }

    public function needsDataAttribute(): bool
    {
        return true;
    }

    public function dataAttribute(): string
    {
        return "Spatie\LaravelData\Attributes\FromRouteParameter";
    }

    public function dataAttributeParam(): string
    {
        return $this->route_parameter;
    }

    public function resourceType(ModelAttributeConfig $config): string
    {
        $base =  'int';

        if($config->isNullable){
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
}
