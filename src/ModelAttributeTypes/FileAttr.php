<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Exception;
use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;

class FileAttr implements ModelAttributeType
{
    public function __construct(
        public bool $isSingle,
        public string $collection_name
    ){}

    public function needsModelCast(): bool
    {
        return false;
    }

    public function modelCast(): string
    {
        throw new Exception('BelongsToAttr does not need a model cast.');
    }

    public function fakerFunction(): string
    {
        throw new Exception('It does not have a function.');
    }

    public function migrationFunction(ModelAttributeConfig $config): string
    {
        throw new Exception('File do not use migrations.');
    }

    public function dataType(ModelAttributeConfig $config): string
    {
        $base = 'UploadedFile';

        if($config->isNullable){
            return '?'.$base;
        }

        return $base;
    }

    public function resourceType(ModelAttributeConfig $config): string
    {
        return 'string';
    }

    public function needsImport(): bool
    {
        return true;
    }

    public function importPath(): string
    {
        return $this->related_model_import;
    }

    public function belongsToTable(): bool
    {
        return false;
    }
}
