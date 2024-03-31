<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Hitocean\CrudGenerator\ModelAttributeConfig;

interface ModelAttributeType
{
    public function needsModelCast(): bool;

    public function modelCast(): string;

    public function fakerFunction(): string;

    public function migrationFunction(ModelAttributeConfig $config): string;
}
