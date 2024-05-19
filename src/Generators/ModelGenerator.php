<?php

namespace Hitocean\CrudGenerator\Generators;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ModelConfig;
use Hitocean\CrudGenerator\ModelAttributeTypes\BelongsToAttr;
use Nette\PhpGenerator\PhpFile;

class ModelGenerator extends FileGenerator
{
    /**
     * @param ModelConfig $config
     */
    public function create($config): void
    {
        $file = new PhpFile();

        $model_import = "Illuminate\Database\Eloquent\Model";
        $has_factory_import = "Illuminate\Database\Eloquent\Factories\HasFactory";
        $belongs_to_import = "Illuminate\Database\Eloquent\Relations\BelongsTo";

        $namespace = $file->addNamespace($config->namespace())
                          ->addUse($model_import)
                          ->addUse($has_factory_import);

        $class = $namespace->addClass($config->modelName)
                           ->setExtends($model_import);

        $class->addTrait($has_factory_import);

        $class->addProperty('table', $config->tableName)
              ->setVisibility('protected');

        $class->addProperty('fillable', $config->attributes->map(fn ($attr) => $attr->name)->toArray())
              ->setVisibility('protected');

        $class->addProperty(
            'casts',
            $config->attributes->filter(fn ($attr) => $attr->type->needsModelCast())->mapWithKeys(
                fn ($attr) => [$attr->name => $attr->type->modelCast()]
            )->toArray()
        )
              ->setVisibility('protected');

        $config->attributes->filter(fn (ModelAttributeConfig $attr) => $attr->type instanceof BelongsToAttr)
                           ->each(function (ModelAttributeConfig $attr) use ($namespace, $class, $belongs_to_import)
                           {
                               $namespace->addUse($attr->type->importPath())
                                         ->addUse($belongs_to_import);

                               $class->addMethod($attr->type->relationName())
                                     ->setReturnType($belongs_to_import)
                                     ->setBody("return \$this->belongsTo({$attr->type->relatedModelClass()}::class);");
                           });

        $this->createFile($config->filePath(), $file);

    }

    public function filePath(ModelConfig $config): string
    {
        return base_path($config->folder . '/Models/' . $config->modelName . '.php');
    }

    public static function getImport(ModelConfig $config): string
    {
        return 'Src\\' . $config->folder . '\\Models\\' . $config->modelName;
    }
}
