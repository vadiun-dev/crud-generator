<?php

namespace Hitocean\CrudGenerator\Generators;

use Hitocean\CrudGenerator\ModelConfig;
use Nette\PhpGenerator\PhpFile;

class ModelGenerator extends FileGenerator
{
    public function create(ModelConfig $config): void
    {
        $file = new PhpFile();

        $model_import = "Illuminate\Database\Eloquent\Model";
        $has_factory_import = "Illuminate\Database\Eloquent\Factories\HasFactory";

        $namespace = $file->addNamespace('Src\\'.$config->folder)
            ->addUse($model_import)
            ->addUse($has_factory_import);

        $class = $namespace->addClass($config->modelName)
            ->setExtends($model_import);

        $class->addTrait($has_factory_import);

        $class->addProperty('table', $config->tableName)
            ->setVisibility('protected');

        $class->addProperty('fillable', $config->attributes->map(fn ($attr) => $attr->name)->toArray())
            ->setVisibility('protected');

        $class->addProperty('casts', $config->attributes->filter(fn ($attr) => $attr->type->needsModelCast())->mapWithKeys(fn ($attr) => [$attr->name => $attr->type->modelCast()])->toArray())
            ->setVisibility('protected');

        $this->createFile($this->filePath($config), $file);

    }

    public function filePath(ModelConfig $config): string
    {
        return base_path('src/'.$config->folder.'/Models/'.$config->modelName.'.php');
    }

    public static function getImport(ModelConfig $config): string
    {
        return 'Src\\'.$config->folder.'\\Models\\'.$config->modelName;
    }
}
