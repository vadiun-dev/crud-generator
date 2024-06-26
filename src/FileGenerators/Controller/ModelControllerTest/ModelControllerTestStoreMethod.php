<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Illuminate\Support\Collection;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ModelControllerTestStoreMethod
{
    public static function create(Collection $inputs, string $method_name, string $model_name, string $controller_name, ClassType $class): Method
    {
        $method = $class->addMethod('it_'.$method_name)
            ->addComment('@test')
            ->setVisibility('public');

        if ($inputs->isNotEmpty()) {
            $method->addBody('$data = [');
            foreach ($inputs as $input) {
                $method->addBody("    '{$input->name}' => {$input->type->fakerTestFunction()},");
            }
            $method->addBody('];');
        }

        $method->addBody("\$this->post(action([{$controller_name}::class, '{$method_name}']), \$data)->assertOk();");

        $method->addBody("\$this->assertDatabaseHas({$model_name}::class, [");
        $inputs->each(fn (ModelAttributeConfig $attr) => $method->addBody("'{$attr->name}' => \$data['{$attr->name}'],"));
        $method->addBody(']);');

        $method->setReturnType('void');

        return $method;
    }
}
