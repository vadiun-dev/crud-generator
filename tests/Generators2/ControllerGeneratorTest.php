<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\Generators\ControllerGenerator;
use Hitocean\CrudGenerator\Generators\ControllerTestGenerator;
use Hitocean\CrudGenerator\Generators\FileConfigs\ControllerConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ControllerTestConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ModelConfig;
use Hitocean\CrudGenerator\Generators\ModelGenerator;
use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttr;

beforeEach(function ()
{
    $this->generator = new ControllerTestGenerator();

    $this->simpleModelConfig = new ControllerTestConfig(
        'Src\Controllers\ClientController',
        'Src\Models\Client',
        collect([
                    new ModelAttributeConfig(
                        'first_name',
                        new StringAttr(),
                        false
                    ),
                ]),
        'tests',
        'Tests',
        collect([])
    );

    $this->generator->create($this->simpleModelConfig);

    $file  = Nette\PhpGenerator\PhpFile::fromCode(
        file_get_contents(base_path('tests/Controllers/ClientControllerTest.php'))
    );
    $class = $file->getClasses();

    $this->classFile = $class['Tests\Controllers\ClientControllerTest'];
});

afterEach(function ()
{
    unlink(base_path('tests/Controllers/ClientControllerTest.php'));
});

it('creates a controller file', function ()
{
    expect(file_exists(base_path('tests/Controllers/ClientControllerTest.php')))->toBeTrue();
});

it('has correct namespace', function ()
{
    expect($this->classFile->getNamespace()->getName())->toBe('Tests\Controllers');
});

it('has correct class name', function ()
{
    expect($this->classFile->getName())->toBe('ClientControllerTest');
});

it('has correct imports', function ()
{
    expect($this->classFile->getNamespace()->getUses())->toBe([
                                                                  'ClientController' => 'Src\Controllers\ClientController',
                                                                  'Client'           => 'Src\Models\Client',
                                                                  'TestCase'         => 'Tests\TestCase',
                                                              ]);
});

it('has correct store method', function ()
{
    $method = $this->classFile->getMethods()['it_store_a_new_model'];
    expect($this->classFile->getMethods())->toHaveKey('it_store_a_new_model')
                                          ->and($method->getComment())->toBe('@test')
                                          ->and($method->getBody())->toContain('$data = [')
                                          ->toContain("'first_name' => \$this->faker->word,")
                                          ->toContain('];')
                                          ->toContain(
                                              "\$this->post(action([/*(n*/\Src\Controllers\ClientController::class, 'store']), \$data)->assertOk();"
                                          )
                                          ->toContain("\$this->assertDatabaseHas(/*(n*/\Src\Models\Client::class, [")
                                          ->toContain("'first_name' => \$data['first_name'],")
                                          ->toContain(']);')
                                          ->and($method->getReturnType())->toBe('void');
});

it('has correct update method', function ()
{
    $method = $this->classFile->getMethods()['it_updates_a_model'];
    expect($this->classFile->getMethods())->toHaveKey('it_updates_a_model')
                                          ->and($method->getComment())->toBe('@test')
                                          ->and($method->getBody())->toContain(
            '$model = /*(n*/\Src\Models\Client::factory()->create();'
        )
                                          ->toContain('$data = [')
                                          ->toContain("'first_name' => \$this->faker->word,")
                                          ->toContain('];')
                                          ->toContain(
                                              "\$this->put(action([/*(n*/\Src\Controllers\ClientController::class, 'update'], \$model->id), \$data)->assertOk();"
                                          )
                                          ->toContain("\$this->assertDatabaseHas(/*(n*/\Src\Models\Client::class, [")
                                          ->toContain("'first_name' => \$data['first_name'],")
                                          ->toContain(']);')
                                          ->and($method->getReturnType())->toBe('void');
});

it('has correct destroy method', function ()
{

    $method = $this->classFile->getMethods()['it_deletes_a_model'];
    expect($this->classFile->getMethods())->toHaveKey('it_deletes_a_model')
                                          ->and($method->getComment())->toBe('@test')
                                          ->and($method->getBody())->toContain(
            "\$model = /*(n*/\Src\Models\Client::factory()->create();"
        )
                                          ->toContain(
                                              "\$this->delete(action([/*(n*/\Src\Controllers\ClientController::class, 'destroy'], \$model->id))->assertOk();"
                                          )
                                          ->toContain(
                                              "\$this->assertDatabaseMissing(/*(n*/\Src\Models\Client::class, ["
                                          )
                                          ->toContain("'id' => \$model->id")
                                          ->toContain(']);')
                                          ->and($method->getReturnType())->toBe('void');
});

it('has correct index method', function ()
{

    $method = $this->classFile->getMethods()['it_returns_a_collection_of_models'];
    expect($this->classFile->getMethods())
        ->toHaveKey('it_returns_a_collection_of_models')
        ->and($method->getComment())->toBe('@test')
        ->and($method->getBody())->toContain('$models = /*(n*/\Src\Models\Client::factory(1)->create();')
        ->toContain("\$this->get(action([/*(n*/\Src\Controllers\ClientController::class, 'index']))->assertOk()")
        ->toContain("->assertExactJson([")
        ->toContain('[')
        ->toContain("'first_name' => \$models[0]->first_name,")
        ->toContain(']')
        ->toContain(']);')
        ->and($method->getReturnType())->toBe('void');

});

it('has correct show method', function ()
{
    $method = $this->classFile->getMethods()['it_returns_a_model'];
    expect($this->classFile->getMethods())
        ->toHaveKey('it_returns_a_model')
        ->and($method->getComment())->toBe('@test')
        ->and($method->getBody())->toContain('$model = /*(n*/\Src\Models\Client::factory()->create();')
        ->toContain("\$this->get(action([/*(n*/\Src\Controllers\ClientController::class, 'show'], \$model->id))")
        ->toContain('->assertOk()')
        ->toContain('->assertExactJson([')
        ->toContain("'first_name' => \$model->first_name,")
        ->toContain(']);')
        ->and($method->getReturnType())->toBe('void');
});


