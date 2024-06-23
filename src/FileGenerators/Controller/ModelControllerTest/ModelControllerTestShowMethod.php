<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest;

use Illuminate\Support\Collection;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ModelControllerTestShowMethod
{
    public static function create(
        Collection $outputs,
        string $method_name,
        string $model_name,
        string $controller_name,
        ClassType $class
    ): Method {
        $method = $class->addMethod('it_'.$method_name)
            ->addComment('@test')
            ->setVisibility('public');

        $method->addBody("\$model = $model_name::factory()->create();")
            ->addBody('')
            ->addBody("\$this->get(action([$controller_name::class, '$method_name'], \$model->id))->assertOk()")
            ->addBody('->assertExactJson([');

        foreach ($outputs as $attr) {
            $method->addBody("    '{$attr->name}' => \$model->{$attr->type->resourceMapProperty($attr)},");
        }
        $method->addBody(']);');

        $method->setReturnType('void');

        return $method;
    }
}
