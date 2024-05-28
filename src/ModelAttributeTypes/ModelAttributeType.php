<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;

interface ModelAttributeType
{
    public function needsModelCast(): bool;

    public function modelCast(): string;

    public function fakerFunction(): string;

    public function fakerTestFunction(): string;

    public function migrationFunction(ModelAttributeConfig $config): string;

    public function dataType(ModelAttributeConfig $config): string;

    public function resourceType(ModelAttributeConfig $config): string;

    public function needsImport(): bool;

    public function importPath(): string;

    public function resourceMapProperty(ModelAttributeConfig $config): string;

    public function needsResourceMap(): bool;

    public function needsDataAttribute(): bool;

    public function dataAttribute(): string;
}
