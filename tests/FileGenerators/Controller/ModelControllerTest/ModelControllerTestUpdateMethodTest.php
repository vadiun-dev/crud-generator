<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest\ModelControllerTestStoreMethod;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest\ModelControllerTestUpdateMethod;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Nette\PhpGenerator\ClassType;

it('creates a update method for the controller test', function () {
    $inputs = collect([
                          new ModelAttributeConfig('title', new StringAttr()),
                          new ModelAttributeConfig('description', new StringAttr())
                      ]);

    $class = new ClassType('TestControllerTest');
    $method = ModelControllerTestUpdateMethod::create($inputs, 'update', 'TestModel', 'TestController', $class);

    $methodBody = $method->getBody();

    expect($method->getName())->toBe('it_update')
      ->and($method->getVisibility())->toBe('public')
      ->and($method->getReturnType())->toBe('void')
      ->and($methodBody)
          ->toContain('$model = TestModel::factory()->create();')
          ->toContain('$data = [')
          ->toContain("'title' => \$this->faker->word,")
          ->toContain("'description' => \$this->faker->word,")
          ->toContain('];')
          ->toContain("\$this->put(action([TestController::class, 'update'], \$model->id), \$data)->assertOk();")
          ->toContain("\$this->assertDatabaseHas(TestModel::class, [")
            ->toContain("'id' => \$model->id,")
          ->toContain("'title' => \$data['title'],")
          ->toContain("'description' => \$data['description'],")
          ->toContain(']);');

});

it('has empty inputs', function (){
    $inputs = collect([]);

    $class = new ClassType('TestControllerTest');
    $method = ModelControllerTestUpdateMethod::create($inputs, 'update', 'TestModel', 'TestController', $class);

    $methodBody = $method->getBody();

    expect($method->getName())->toBe('it_update')
                              ->and($method->getVisibility())->toBe('public')
                              ->and($method->getReturnType())->toBe('void')
                              ->and($methodBody)
                              ->toContain('$model = TestModel::factory()->create();')
                              ->not->toContain('$data = [')
                              ->not->toContain("'title' => \$this->faker->word,")
                              ->not->toContain("'description' => \$this->faker->word,")
                              ->not->toContain('];')
                              ->toContain("\$this->put(action([TestController::class, 'update'], \$model->id), \$data)->assertOk();")
                              ->toContain("\$this->assertDatabaseHas(TestModel::class, [")
                              ->toContain("'id' => \$model->id,")
                              ->not->toContain("'title' => \$data['title'],")
                              ->not->toContain("'description' => \$data['description'],")
                              ->toContain(']);');
});
