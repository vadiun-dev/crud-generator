<?php

namespace Hitocean\CrudGenerator\Generators;

use Hitocean\CrudGenerator\Generators\FileConfigs\FactoryConfig;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpFile;

class FactoryGenerator extends FileGenerator
{
    /**
     * @param  FactoryConfig  $config
     */
    public function create($config): void
    {
        $file = new PhpFile();

        $factory_import = "Illuminate\Database\Eloquent\Factories\Factory";

        $namespace = $file->addNamespace($config->namespace())
            ->addUse($config->model_import)
            ->addUse($factory_import);

        $class = $namespace->addClass($config->className())
            ->setExtends($factory_import);

        $class->addProperty('model', new Literal($config->modelClassName().'::class'))
            ->setVisibility('protected');

        $class->addMethod('definition')
            ->addBody('return [')
            ->addBody(
                $config->attributes->map(fn ($attr) => "'{$attr->name}' => {$attr->type->fakerFunction()},")->implode("\n")
            )
            ->addBody('];');

        $config->attributes->filter(fn ($attr) => $attr->type->needsImport())->each(function ($attr) use ($namespace) {
            $namespace->addUse($attr->type->importPath());
        });

        $this->createFile($config->filePath(), $file);
    }
}
