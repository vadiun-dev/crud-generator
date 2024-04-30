<?php

namespace Hitocean\CrudGenerator;

use DirectoryIterator;
use Hitocean\CrudGenerator\ModelAttributeTypes\BooleanAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\FloatAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\IntAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttr;

class ModelConfigFactory
{
    public static function makeConfig(array $data): ModelConfig
    {
        static::validateDataStructure($data);

        return new ModelConfig(
            $data['modelName'],
            $data['folder'],
            collect($data['attributes'])->map(
                fn ($attribute) => new ModelAttributeConfig(
                    $attribute['name'],
                    static::mapAttrType($attribute['type']),
                    static::isOptional($attribute['type'])
                )
            ),
            $data['tableName'],
            $data['makeCrud']
        );
    }

    private static function mapAttrType(string $type)
    {
        if (static::isOptional($type)) {
            $type = str_replace('?', '', $type);
        }

        return match ($type) {
            'string' => new StringAttr(),
            'integer', 'int' => new IntAttr(),
            'boolean', 'bool' => new BooleanAttr(),
            'datetime' => new DateTimeAttr(),
            'float' => new FloatAttr(),
            default => throw new \Exception("Invalid attribute type: $type"),
        };
    }

    private static function isOptional(string $type): bool
    {
        return str_contains($type, '?');
    }

    public static function validateDataStructure(array $data): void
    {
        $requiredKeys = ['modelName', 'folder', 'attributes', 'tableName', 'makeCrud'];
        $missingKeys = collect($requiredKeys)->diff(array_keys($data));

        if ($missingKeys->isNotEmpty()) {
            throw new \Exception('Missing keys: '. $missingKeys->implode(', '));
        }


        if (! is_array($data['attributes'])) {
            throw new \Exception('Attributes must be an array');
        }

        $attributes = collect($data['attributes']);
        $missingAttributeKeys = $attributes->map(fn ($attribute) => collect(['name', 'type'])->diff(array_keys($attribute)))
            ->filter(fn ($missingKeys) => $missingKeys->isNotEmpty());

        if ($missingAttributeKeys->isNotEmpty()) {
            throw new \Exception('Every attribute must have a name and a type');
        }
    }
}