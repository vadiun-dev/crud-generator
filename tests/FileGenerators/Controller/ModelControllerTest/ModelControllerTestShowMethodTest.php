<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest\ModelControllerTestIndexMethod;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest\ModelControllerTestShowMethod;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Nette\PhpGenerator\ClassType;

it('creates a show method for the controller test', function () {
    $inputs = collect([
                          new ModelAttributeConfig('title', new StringAttr()),
                          new ModelAttributeConfig('description', new StringAttr())
                      ]);

    $class = new ClassType('TestControllerTest');
    $method = ModelControllerTestShowMethod::create($inputs, 'show', 'TestModel', 'TestController', $class);

    $methodBody = $method->getBody();

    expect($method->getName())->toBe('it_show')
      ->and($method->getVisibility())->toBe('public')
      ->and($method->getReturnType())->toBe('void')
      ->and($methodBody)
          ->toContain('$model = TestModel::factory()->create();')
          ->toContain("\$this->get(action([TestController::class, 'show'], \$model->id))->assertOk()")
        ->toContain('->assertExactJson([')
        ->toContain("'title' => \$model->title,")
        ->toContain("'description' => \$model->description,")
        ->toContain(']);');


});

