<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest\ModelControllerTestIndexMethod;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Nette\PhpGenerator\ClassType;

it('creates a index method for the controller test', function () {
    $inputs = collect([
        new ModelAttributeConfig('title', new StringAttr()),
        new ModelAttributeConfig('description', new StringAttr()),
    ]);

    $class = new ClassType('TestControllerTest');
    $method = ModelControllerTestIndexMethod::create($inputs, 'index', 'TestModel', 'TestController', $class);

    $methodBody = $method->getBody();

    expect($method->getName())->toBe('it_index')
        ->and($method->getVisibility())->toBe('public')
        ->and($method->getReturnType())->toBe('void')
        ->and($methodBody)
        ->toContain('$models = TestModel::factory(1)->create();')
        ->toContain("\$this->get(action([TestController::class, 'index']))->assertOk()")
        ->toContain('->assertExactJson([')
        ->toContain('[')
        ->toContain("'title' => \$models[0]->title,")
        ->toContain("'description' => \$models[0]->description,")
        ->toContain(']')
        ->toContain(']);');

});
