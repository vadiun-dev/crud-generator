<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\ModelController;

use Illuminate\Support\Collection;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ModelControllerDeleteMethod
{
    public static function create(Collection $inputs, string $method_name, string $model_name, ClassType $class): Method
    {
        $method = $class->addMethod($method_name)
            ->setVisibility('public');

        if ($inputs->isNotEmpty()) {
            $method->addBody('$data = [');
            foreach ($inputs as $input) {
                $method->addBody("    '{$input->name}' => \$data->{$input->name},");
            }
            $method->addBody('];');
        }

        $method->addBody("$model_name::destroy(\$id);");

        return $method;
    }
}
