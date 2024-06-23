<?php

namespace Hitocean\CrudGenerator\Helpers;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;
use Illuminate\Support\Collection;
use function base_path;
use function class_basename;
use function collect;
use function compact;
use function is_subclass_of;
use function pathinfo;
use function preg_match;
use const PATHINFO_FILENAME;

class ModelHelper
{
    /**
     * Get all models in the project.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllModels(): Collection
    {
        $modelPath = base_path('src/Domain');

        $models = collect();

        if (File::exists($modelPath)) {
            $files = File::allFiles($modelPath);
            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $namespace = static::getNamespace($file->getPathname());

                    $classname = pathinfo($file->getFilename(), PATHINFO_FILENAME);

                    $fullClass = $namespace . $classname;
                    if (is_subclass_of($fullClass, 'Illuminate\Database\Eloquent\Model') && !(new \ReflectionClass($fullClass))->isAbstract()) {
                        $models->push(static::getModelDetails($fullClass));
                    }
                }
            }
        }

        return $models;
    }


    /**
     * Get detailed information about the model.
     *
     * @param string $modelClass
     * @return array
     */


    private static function getModelDetails(string $modelClass): array
    {
        $modelInstance = new $modelClass;
        $reflection = new ReflectionClass($modelClass);

        $name = class_basename($modelClass);
        $import = $modelClass;
        $table = $modelInstance->getTable();

        // Get columns and their types from the table
        $columns = Schema::getColumnListing($table);
        $properties = collect($columns)->map(function ($column) use ($table) {
            return [
                'name' => $column,
                'type' => static::mapColumnTypeToPhpType(Schema::getColumnType($table, $column)),
            ];
        });

        return compact('name', 'import', 'table', 'properties');
    }


    private static function mapColumnTypeToPhpType(string $columnType): string
    {
        $typeMap = [
            'integer' => 'int',
            'bigint' => 'int',
            'smallint' => 'int',
            'tinyint' => 'int',
            'varchar' => 'string',
            'char' => 'string',
            'text' => 'string',
            'longtext' => 'string',
            'enum' => 'string',
            'set' => 'string',
            'boolean' => 'bool',
            'datetime' => 'date',
            'date' => 'date',
            'timestamp' => 'date',
            'time' => 'date',
            'float' => 'float',
            'double' => 'float',
            'decimal' => 'float',
            'json' => 'array',
        ];


        return $typeMap[$columnType] ?? 'mixed';
    }
    /**
     * Get the namespace of the class from a file.
     *
     * @param string $filePath
     * @return string
     */
    private static function getNamespace(string $filePath): string
    {
        $content = File::get($filePath);
        if (preg_match('/namespace\s+(.+?);/', $content, $matches)) {
            return $matches[1] . '\\';
        }
        return '';
    }
}
