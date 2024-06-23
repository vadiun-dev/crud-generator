<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest;

use Illuminate\Support\Collection;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ModelControllerTestIndexMethod
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

        $method->addBody("\$models = $model_name::factory(1)->create();")
            ->addBody('')
            ->addBody("\$this->get(action([$controller_name::class, '$method_name']))->assertOk()")
            ->addBody('->assertExactJson([')
            ->addBody('[');

        foreach ($outputs as $attr) {
            $method->addBody("    '{$attr->name}' => \$models[0]->{$attr->type->resourceMapProperty($attr)},");
        }
        $method->addBody(']')
            ->addBody(']);');

        $method->setReturnType('void');

        return $method;
    }
}
