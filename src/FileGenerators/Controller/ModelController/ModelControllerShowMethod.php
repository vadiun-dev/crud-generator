<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\ModelController;

use Illuminate\Support\Collection;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ModelControllerShowMethod
{
    public static function create(Collection $inputs, string $method_name, string $model_name, ClassType $class, ?string $resource_import): Method
    {
        $method = $class->addMethod($method_name)
                        ->setVisibility('public');



        $resourceClass = static::getShortClassName($resource_import);
        $method->addBody("return $resourceClass::from($model_name::findOrFail(\$id));");

        return $method;
    }

    private static function getShortClassName(string $class): string
    {
        $parts = explode('\\', $class);
        return end($parts);
    }
}
