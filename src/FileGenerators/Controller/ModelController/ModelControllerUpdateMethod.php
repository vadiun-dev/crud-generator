<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller\ModelController;

use Illuminate\Support\Collection;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;

class ModelControllerUpdateMethod
{
    public static function create(Collection $inputs, string $method_name, string $model_name, ClassType $class, ?string $data_import): Method
    {
        $method = $class->addMethod($method_name)
            ->setVisibility('public');

        $method->addBody("\$model = $model_name::findOrFail(\$data->id);");

        if ($inputs->isNotEmpty()) {
            $method->addBody('$model->update([');
            foreach ($inputs as $input) {
                $method->addBody("    '{$input->name}' => \$data->{$input->name},");
            }
            $method->addBody(']);');
        }

        if ($data_import) {
            $method->addParameter('data')
                ->setType($data_import);
        }

        return $method;
    }
}
