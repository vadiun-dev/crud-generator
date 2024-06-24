<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelController\ModelControllerDeleteMethod;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Nette\PhpGenerator\ClassType;

it('creates a delete method for the controller test', function () {
    $inputs = collect([
        new ModelAttributeConfig('title', new StringAttr()),
        new ModelAttributeConfig('description', new StringAttr()),
    ]);

    $class = new ClassType('CafeteraController');
    $method = ModelControllerDeleteMethod::create($inputs, 'store', 'TestModel', $class);

    $methodBody = $method->getBody();

    expect($method->getName())->toBe('store')
        ->and($method->getVisibility())->toBe('public')
        ->and($method->getParameters())->toHaveKey('id')
        ->and($method->getParameters()['id']->getType())->toBe('int')
        ->and($methodBody)
        ->toContain('$data = [')
        ->toContain("'title' => \$data->title,")
        ->toContain("'description' => \$data->description,")
        ->toContain('];')
        ->toContain('TestModel::destroy($id);');

});
