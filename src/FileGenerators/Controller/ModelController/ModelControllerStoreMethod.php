<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\ModelController;

use Illuminate\Support\Collection;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ModelControllerStoreMethod
{
    public static function create(Collection $inputs, string $method_name, string $model_name, ClassType $class, ?string $data_import): Method
    {
        $method = $class->addMethod($method_name)
                        ->setVisibility('public');

        if ($inputs->isNotEmpty()) {
            $method->addBody("\$model = $model_name::create([");

            foreach ($inputs as $input) {
                $method->addBody("    '{$input->name}' => \$data->{$input->name},");
            }
            $method->addBody(']);');
        }

        if($data_import){
            $method->addParameter('data')
                   ->setType($data_import);
        }

        return $method;
    }
}
