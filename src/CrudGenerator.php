<?php

namespace Hitocean\CrudGenerator;

use DirectoryIterator;
use Hitocean\CrudGenerator\ModelAttributeTypes\BooleanAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\FloatAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\IntAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttr;
use Illuminate\Support\Collection;

class CrudGenerator
{
    /**
     * @return array<ModelConfig>
     */
    public static function handle(): array
    {
        $iterator = new DirectoryIterator(base_path('generators'));
        foreach ($iterator as $json_conf)
        {
            if (!$json_conf->isDot()) {
                $configData = json_decode(file_get_contents($json_conf->getPath().'/'.$json_conf->getFilename()));
                $configs[] = new ModelConfig(
                    $configData->modelName,
                    $configData->folder,
                    collect($configData->attributes)->map(fn($attribute) =>
                         new ModelAttributeConfig($attribute->name, static::mapAttrType($attribute->type), static::isOptional($attribute->type))
                    ),
                    $configData->tableName,
                    $configData->makeCrud
                );
            }
        }
        return $configs;
    }

    private static function mapAttrType(string $type)
    {
        if(static::isOptional($type))
        {
            $type = str_replace('?', '', $type);
        }
        return match ($type) {
            'string' => new StringAttr(),
            'integer','int' => new IntAttr(),
            'boolean','bool' => new BooleanAttr(),
            'datetime' => new DateTimeAttr(),
            'float' => new FloatAttr(),
            default => throw new \Exception('Invalid attribute type'),
        };
    }

    private static function isOptional(string $type): bool
    {
        return str_contains($type, '?');
    }
}
