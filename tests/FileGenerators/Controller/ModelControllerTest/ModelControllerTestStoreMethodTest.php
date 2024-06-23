<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest\ModelControllerTestStoreMethod;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Nette\PhpGenerator\ClassType;

it('creates a store method for the controller test', function () {
    $inputs = collect([
        new ModelAttributeConfig('title', new StringAttr()),
        new ModelAttributeConfig('description', new StringAttr()),
    ]);

    $class = new ClassType('TestControllerTest');
    $method = ModelControllerTestStoreMethod::create($inputs, 'store', 'TestModel', 'TestController', $class);

    $methodBody = $method->getBody();

    expect($method->getName())->toBe('it_store')
        ->and($method->getVisibility())->toBe('public')
        ->and($method->getReturnType())->toBe('void')
        ->and($methodBody)
        ->toContain('$data = [')
        ->toContain("'title' => \$this->faker->word,")
        ->toContain("'description' => \$this->faker->word,")
        ->toContain('];')
        ->toContain("\$this->post(action([TestController::class, 'store']), \$data)->assertOk();")
        ->toContain('$this->assertDatabaseHas(TestModel::class, [')
        ->toContain("'title' => \$data['title'],")
        ->toContain("'description' => \$data['description'],")
        ->toContain(']);');

});

it('has empty inputs', function () {
    $inputs = collect([]);

    $class = new ClassType('TestControllerTest');
    $method = ModelControllerTestStoreMethod::create($inputs, 'store', 'TestModel', 'TestController', $class);

    $methodBody = $method->getBody();

    expect($method->getName())->toBe('it_store')
        ->and($method->getVisibility())->toBe('public')
        ->and($method->getReturnType())->toBe('void')
        ->and($methodBody)
        ->not->toContain('$data = [')
        ->toContain("\$this->post(action([TestController::class, 'store']), \$data)->assertOk();")
        ->toContain('$this->assertDatabaseHas(TestModel::class, [')
        ->toContain(']);');
});
