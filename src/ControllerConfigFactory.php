<?php

namespace Hitocean\CrudGenerator;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerMethodConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ModelControllerConfig;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BelongsToAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\DateTimeAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\FloatAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\IntAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;

use function collect;

class ControllerConfigFactory
{
    public static function makeConfig(array $data): ModelControllerConfig
    {
        //static::validateDataStructure($data);

        return new ModelControllerConfig(
            $data['controller_name'],
            $data['model_import'],
            $data['root_folder'],
            $data['root_namespace'],
            $data['test_path'],
            collect($data['methods'])->map(fn ($m) => new ControllerMethodConfig(
                name: $m['name'],
                route_method: $m['route_method'],
                inputs: collect($m['inputs'])->map(
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
                data_class_path: $m['data_class_path'],
                data_class_import: $m['data_class_import'],
                resource_class_import: $m['resource_class_import'],
                resource_class_path: $m['resource_class_path'],
                outputs: collect($m['outputs'])->map(
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
            )
            )
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
        $requiredKeys = ['controller_name', 'root_folder', 'model_attributes', 'root_namespace', 'model_import'];
        $missingKeys = collect($requiredKeys)->diff(array_keys($data));

        if ($missingKeys->isNotEmpty()) {
            throw new \Exception('Missing keys: '.$missingKeys->implode(', '));
        }

        if (! is_array($data['model_attributes'])) {
            throw new \Exception('Attributes must be an array');
        }

        $attributes = collect($data['model_attributes']);
        $missingAttributeKeys = $attributes->map(
            fn ($attribute) => collect(['name', 'type'])->diff(array_keys($attribute))
        )
            ->filter(fn ($missingKeys) => $missingKeys->isNotEmpty());

        if ($missingAttributeKeys->isNotEmpty()) {
            throw new \Exception('Every attribute must have a name and a type');
        }
    }
}
