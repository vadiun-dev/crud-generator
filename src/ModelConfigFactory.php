<?php

namespace Hitocean\CrudGenerator;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Model\FileConfigs\ModelConfig;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BelongsToAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\DateTimeAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\FloatAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\IntAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;

class ModelConfigFactory
{
    public static function makeConfig(array $data): ModelConfig
    {
        static::validateDataStructure($data);

        return new ModelConfig(
            $data['modelName'],
            $data['root_folder'],
            $data['root_namespace'],
            collect($data['attributes'])->map(
                fn ($attribute) => new ModelAttributeConfig(
                    $attribute['name'],
                    static::mapAttrType(
                        $attribute['type'],
                        $attribute['model_import'] ?? null,
                        $attribute['table'] ?? null,
                        $attribute['relation_name'] ?? null
                    ),
                    static::isOptional($attribute['type'])
                )
            ),
            $data['tableName']
        );
    }

    private static function mapAttrType(string $type, ?string $model_import, ?string $table, ?string $relation_name)
    {
        if (static::isOptional($type)) {
            $type = str_replace('?', '', $type);
        }

        return match ($type) {
            'string' => new StringAttr(),
            'integer', 'int' => new IntAttr(),
            'boolean', 'bool' => new BooleanAttr(),
            'datetime', 'date' => new DateTimeAttr(),
            'float' => new FloatAttr(),
            'belongsTo' => new BelongsToAttr($model_import, $table, $relation_name),
            default => throw new \Exception("Invalid attribute type: $type"),
        };
    }

    private static function isOptional(string $type): bool
    {
        return str_contains($type, '?');
    }

    public static function validateDataStructure(array $data): void
    {
        $requiredKeys = ['modelName', 'root_folder', 'root_namespace', 'attributes', 'tableName'];
        $missingKeys = collect($requiredKeys)->diff(array_keys($data));

        if ($missingKeys->isNotEmpty()) {
            throw new \Exception('Missing keys: '.$missingKeys->implode(', '));
        }

        if (! is_array($data['attributes'])) {
            throw new \Exception('Attributes must be an array');
        }

        $attributes = collect($data['attributes']);
        $missingAttributeKeys = $attributes->map(
            fn ($attribute) => collect(['name', 'type'])->diff(array_keys($attribute))
        )
            ->filter(fn ($missingKeys) => $missingKeys->isNotEmpty());

        if ($missingAttributeKeys->isNotEmpty()) {
            throw new \Exception('Every attribute must have a name and a type');
        }
    }
}
