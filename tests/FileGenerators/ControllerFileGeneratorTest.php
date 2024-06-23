<?php

use Hitocean\CrudGenerator\FileGenerators\Controller\ControllerFileGenerator;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerMethodConfig;
use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttributeType;
use Illuminate\Support\Facades\File;
use Nette\PhpGenerator\PhpFile;

beforeEach(function () {
    // Instancia del generador
    $this->generator = new ControllerFileGenerator();

    // Configuración simple para el controlador
    $this->simpleConfig = new ControllerConfig(
        'CafeteraController',
        'tests/src/Application/Cafetera/Controllers',
        'Tests\\src\\Application\\Cafetera\\Controllers',
        collect([
                    new ControllerMethodConfig(
                        'index',
                        'GET',
                        collect(),
                        null,
                        null,
                        null,
                        null,
                        collect()
                    ),
                    new ControllerMethodConfig(
                        'store',
                        'POST',
                        collect(),
                        'App\\Http\\Requests\\StoreCafeteraRequest',
                        'App\\Http\\Requests\\StoreCafeteraRequest',
                        'App\\Http\\Resources\\CafeteraResource',
                        'App\\Http\\Resources\\CafeteraResource',
                        collect()
                    )
                ]),
        'tests/src/Application/Cafetera/Controllers/Tests'
    );

    // Generar el archivo del controlador
    $this->generator->create($this->simpleConfig);

    // Cargar el archivo PHP generado
    $file = PhpFile::fromCode(
        file_get_contents(base_path('tests/src/Application/Cafetera/Controllers/CafeteraController.php'))
    );

    $class = $file->getClasses();

    $this->classFile = $class['Tests\\src\\Application\\Cafetera\\Controllers\\CafeteraController'];
});

afterEach(function () {
    // Borrar el directorio de prueba
    File::deleteDirectory(base_path('tests'));
});

it('creates a controller file')
    ->expect(fn() => file_exists(base_path('tests/src/Application/Cafetera/Controllers/CafeteraController.php')))
    ->toBeTrue();

it('has correct namespace')
    ->expect(fn() => $this->classFile->getNamespace()->getName())
    ->toBe('Tests\\src\\Application\\Cafetera\\Controllers');

it('has correct class name')
    ->expect(fn() => $this->classFile->getName())
    ->toBe('CafeteraController');

it('has correct imports')
    ->expect(fn() => $this->classFile->getNamespace()->getUses())
    ->toBe([
               'StoreCafeteraRequest' => 'App\\Http\\Requests\\StoreCafeteraRequest',
               'CafeteraResource' => 'App\\Http\\Resources\\CafeteraResource',
           ]);

it('has correct index method', function () {
    $method = $this->classFile->getMethods()['index'];
    expect($this->classFile->getMethods())->toHaveKey('index')
                                          ->and($method->getBody())
                                          ->toContain('// TODO: Implement index logic')
                                          ->and($method->getReturnType())->toBe(null);
});

it('has correct store method', function () {
    $method = $this->classFile->getMethods()['store'];
    expect($this->classFile->getMethods())->toHaveKey('store')
        ->and($method->getParameters())->toHaveCount(1)
        ->and($method->getParameters()['data']->getName())->toBe('data')
        ->and($method->getParameters()['data']->getType())->toBe('\App\Http\Requests\StoreCafeteraRequest')
        ->and($method->getBody())
          ->toContain('return /*(n*/\App\Http\Resources\CafeteraResource::from($data);')
        ->and($method->getReturnType())->toBe(null);
});
