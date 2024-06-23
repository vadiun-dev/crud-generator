<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelController\ModelControllerIndexMethod;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelController\ModelControllerStoreMethod;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Nette\PhpGenerator\ClassType;

it('creates a index method for the controller test', function () {
    $inputs = collect([
                          new ModelAttributeConfig('title', new StringAttr()),
                          new ModelAttributeConfig('description', new StringAttr())
                      ]);

    $class = new ClassType('CafeteraController');
    $method = ModelControllerIndexMethod::create($inputs, 'index', 'TestModel', $class, 'Src\\Models\\TestModel');

    $methodBody = $method->getBody();

    expect($method->getName())->toBe('index')
                              ->and($method->getVisibility())->toBe('public')
                              ->and($methodBody)

        ->toContain('return TestModel::collection(TestModel::all());');

});
