<?php

namespace Hitocean\CrudGenerator\Generators;

use Hitocean\CrudGenerator\ModelAttributeConfig;
use Hitocean\CrudGenerator\ModelConfig;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpFile;

class MigrationGenerator extends FileGenerator
{
    public function create(ModelConfig $config): void
    {
        $file = new PhpFile();

        $migration_import = "Illuminate\Database\Migrations\Migration";
        $blueprint_import = "Illuminate\Database\Schema\Blueprint";
        $schema_import = "Illuminate\Support\Facades\Schema";

        $file->addUse($migration_import)
            ->addUse($schema_import)
            ->addUse($blueprint_import);

        $class = $file->addClass($config->modelName.'Migration')
                           ->setExtends($migration_import);

        $class->addMethod('up')
              ->addBody('Schema::create(\''.$config->tableName.'\', function (Blueprint $table) {')
            ->addBody('                $table->id();')
            ->addBody(
                $config->attributes->map(fn(ModelAttributeConfig $attr) => "\$table->{$attr->type->migrationFunction($attr)};" )->implode("\n")
            )
            ->addBody('                $table->timestamps();')
            ->addBody('            });')

              ->setReturnType('void');

        $this->createFile(database_path('migrations/'.$config->modelName.'.php'), $file);
    }
}
