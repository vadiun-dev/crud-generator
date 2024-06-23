<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelController\ModelControllerStoreMethod;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Nette\PhpGenerator\ClassType;

it('creates a store method for the controller test', function () {
    $inputs = collect([
        new ModelAttributeConfig('title', new StringAttr()),
        new ModelAttributeConfig('description', new StringAttr()),
    ]);

    $class = new ClassType('CafeteraController');
    $method = ModelControllerStoreMethod::create($inputs, 'store', 'TestModel', $class, 'Src\\Models\\TestModel');

    $methodBody = $method->getBody();

    expect($method->getName())->toBe('store')
        ->and($method->getVisibility())->toBe('public')
        ->and($method->getParameters()['data']->getType())->toBe('Src\Models\TestModel')
        ->and($methodBody)
        ->toContain('$model = TestModel::create([')
        ->toContain("'title' => \$data->title,")
        ->toContain("'description' => \$data->description,")
        ->toContain(']);');

});
