<?php

namespace Hitocean\CrudGenerator\FileGenerators\Model;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\FileGenerator;
use Nette\PhpGenerator\ClassType;

class MigrationGenerator extends FileGenerator
{
    public function create($config): void
    {

        $f = "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration";

        $class = new ClassType(null);

        $class->addMethod('up')
            ->addBody('Schema::create(\''.$config->tableName.'\', function (Blueprint $table) {')
            ->addBody('                $table->id();')
            ->addBody(
                $config->attributes->map(fn (ModelAttributeConfig $attr) => "\$table->{$attr->type->migrationFunction($attr)};")->implode("\n")
            )
            ->addBody('                $table->timestamps();')
            ->addBody('            });')
            ->setReturnType('void');

        $class->addMethod('down')
            ->addBody('Schema::dropIfExists(\''.$config->tableName.'\');')
            ->setReturnType('void');

        $filename = now()->format('Y_m_d_His').'_create_'.$config->tableName.'_table';
        $this->createFile(database_path('migrations/'.$filename.'.php'), $f.$class.';');
    }
}
