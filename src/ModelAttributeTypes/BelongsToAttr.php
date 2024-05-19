<?php

namespace Hitocean\CrudGenerator\ModelAttributeTypes;

use Exception;
use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;

class BelongsToAttr implements ModelAttributeType
{
    public function __construct(
        private string $related_model_import,
        private string $related_model_table,
        private string $relation_name
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
        return "{$this->relatedModelClass()}::factory()->create()->id";
    }

    public function migrationFunction(ModelAttributeConfig $config): string
    {
        $base = "foreignId('{$config->name}')->constrained('{$this->related_model_table}')";

        if ($config->isNullable) {
            return $base.'->nullable()';
        }

        return $base;
    }

    public function dataType(ModelAttributeConfig $config): string
    {
        $base =  'int';

        if($config->isNullable){
            return '?'.$base;
        }

        return $base;
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
        return true;
    }

    public function importPath(): string
    {
        return $this->related_model_import;
    }

    public function relationName(): string
    {
        return $this->relation_name;
    }

    public function relatedModelClass(): string
    {
        return class_basename($this->related_model_import);
    }


}
