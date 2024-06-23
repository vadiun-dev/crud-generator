<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Illuminate\Support\Collection;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ModelControllerTestDeleteMethod
{
    public static function create(Collection $inputs, string $method_name, string $model_name, string $controller_name, ClassType $class): Method
    {
        $method = $class->addMethod('it_' . $method_name)
                        ->addComment('@test')
                        ->setVisibility('public');

        $method->addBody("\$model = $model_name::factory()->create();")
            ->addBody('')
               ->addBody("\$this->delete(action([$controller_name::class, '$method_name'], \$model->id))->assertOk();")
            ->addBody('')
               ->addBody("\$this->assertDatabaseMissing($model_name::class, ['id' => \$model->id]);");

        $method->setReturnType('void');

        return $method;
    }
}
