<?php

namespace Hitocean\CrudGenerator\Generators;

use Hitocean\CrudGenerator\ModelConfig;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpFile;

class FactoryGenerator extends FileGenerator
{
    public function create(ModelConfig $config): void
    {
        $file = new PhpFile();

        $factory_import = "Illuminate\Database\Eloquent\Factories\Factory";
        $model_import = ModelGenerator::getImport($config);

        $namespace = $file->addNamespace('Database\Factories')
                          ->addUse($model_import)
                          ->addUse($factory_import);

        $class = $namespace->addClass($config->modelName.'Factory')
                           ->setExtends($factory_import);


        $class->addProperty('model', new Literal($config->modelName.'::class'))
              ->setVisibility('protected');

        $class->addMethod('definition')
              ->addBody('return [')
              ->addBody(
                collect($config->attributes)->map(fn($attr) => "'{$attr->name}' => \$this->faker->{$attr->type->fakerFunction()}," )->implode("\n")
              )
        ->addBody('];');

        $this->createFile(database_path('factories/'.$config->modelName.'Factory.php'), $file);
    }
}
