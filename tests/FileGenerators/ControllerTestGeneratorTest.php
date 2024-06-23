<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ControllerTestGenerator;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerMethodConfig;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Illuminate\Support\Facades\File;
use Nette\PhpGenerator\PhpFile;

beforeEach(function () {
    $this->generator = new ControllerTestGenerator();

    $this->simpleConfig = new ControllerConfig(
        'CafeteraController',
        'tests/src/Application/Cafetera/Controllers',
        'Tests\\src\\Application\\Cafetera\\Controllers',
        collect([
            new ControllerMethodConfig(
                'index',
                'get',
                collect([new ModelAttributeConfig('id', new StringAttr())]),
                null,
                null,
                'App\\Http\\Resources\\CafeteraResource',
                'App\\Http\\Resources\\CafeteraResource',
                collect([new ModelAttributeConfig('title', new StringAttr())])
            ),
            new ControllerMethodConfig(
                'store',
                'post',
                collect([
                    new ModelAttributeConfig('title', new StringAttr()),
                ]),
                'App\\Http\\Requests\\StoreCafeteraRequest',
                'App\\Http\\Resources\\CafeteraResource',
                'App\\Http\\Resources\\CafeteraResource',
                'App\\Http\\Resources\\CafeteraResource',
                collect([new ModelAttributeConfig('title', new StringAttr())])
            ),
        ]),
        'tests/src/Application/Cafetera/Controllers/Tests'
    );

    $this->generator->create($this->simpleConfig);

    $file = PhpFile::fromCode(
        file_get_contents(base_path('tests/src/Application/Cafetera/Controllers/Tests/CafeteraControllerTest.php'))
    );

    $class = $file->getClasses();

    $this->classFile = $class['Tests\\src\\Application\\Cafetera\\Controllers\\Tests\\CafeteraControllerTest'];
});

afterEach(function () {
    File::deleteDirectory(base_path('tests'));
});

it('creates a controller test file')
    ->expect(fn () => file_exists(base_path('tests/src/Application/Cafetera/Controllers/Tests/CafeteraControllerTest.php')))
    ->toBeTrue();

it('has correct namespace')
    ->expect(fn () => $this->classFile->getNamespace()->getName())
    ->toBe('Tests\\src\\Application\\Cafetera\\Controllers\\Tests');

it('has correct class name')
    ->expect(fn () => $this->classFile->getName())
    ->toBe('CafeteraControllerTest');

it('has correct imports')
    ->expect(fn () => $this->classFile->getNamespace()->getUses())
    ->toBe([
        'UploadedFile' => 'Illuminate\\Http\\UploadedFile',
        'File' => 'Illuminate\\Support\\Facades\\File',
        'Roles' => 'Src\\Domain\\User\\Enums\\Roles',
        'User' => 'Src\\Domain\\User\\Models\\User',
        'CafeteraController' => 'Tests\\src\\Application\\Cafetera\\Controllers\\CafeteraController',
    ]);

it('has correct setUp method', function () {
    $method = $this->classFile->getMethods()['setUp'];
    expect($this->classFile->getMethods())->toHaveKey('setUp')
        ->and($method->getBody())
        ->toContain('parent::setUp();')
        ->toContain('$user = /*(n*/\Src\Domain\User\Models\User::factory()->withRole(/*(n*/\Src\Domain\User\Enums\Roles::CONTENTS)->create();')
        ->toContain('$this->actingAs($user);')
        ->and($method->getReturnType())->toBe('void');
});

it('has correct index method', function () {
    $method = $this->classFile->getMethods()['it_index'];
    expect($this->classFile->getMethods())->toHaveKey('it_index')
        ->and($method->getBody())
        ->toContain('$data = [')
        ->toContain('\'id\' => $this->faker->word,')
        ->toContain('];')
        ->toContain('$response = $this->get(action([/*(n*/\Tests\src\Application\Cafetera\Controllers\CafeteraController::class, \'index\']), $data ?? []);')
        ->toContain('$response->assertOk();')
        ->toContain('->assertExactJson([')
        ->toContain('$model->title')
        ->and($method->getReturnType())->toBe('void');
});
