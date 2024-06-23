<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerMethodConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ModelControllerConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelController\ModelControllerGenerator;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Illuminate\Support\Facades\File;
use Nette\PhpGenerator\PhpFile;

beforeEach(function () {
    $this->generator = new ModelControllerGenerator();

    $this->simpleConfig = new ModelControllerConfig(
        'CafeteraController',
        'Src\\Domain\\Cafetera\\Models\\Cafetera',
        'src/Application/Cafetera/Controllers',
        'Src\\Application\\Cafetera\\Controllers',
        'Src\\Application\\Cafetera\\Controllers',
        collect([
                    new ControllerMethodConfig(
                        'index',
                        'GET',
                        collect(),
                        null,
                        null,
                        'App\\Http\\Resources\\CafeteraResource',
                        'App\\Http\\Resources\\CafeteraResource',
                        collect([new ModelAttributeConfig('title', new StringAttr())])
                    ),
                    new ControllerMethodConfig(
                        'store',
                        'POST',
                        collect([
                                    new ModelAttributeConfig('title', new StringAttr()),
                                    new ModelAttributeConfig('description', new StringAttr())
                                ]),

                        'App\\Http\\Requests\\StoreCafeteraRequest',
                        'App\\Http\\Requests\\StoreCafeteraRequest',
                        'App\\Http\\Resources\\CafeteraResource',
                        'App\\Http\\Resources\\CafeteraResource',
                        collect([new ModelAttributeConfig('title', new StringAttr())])
                    ),
                    new ControllerMethodConfig(
                        'update',
                        'put',
                        collect([
                                    new ModelAttributeConfig('title', new StringAttr()),
                                    new ModelAttributeConfig('description', new StringAttr())
                                ]),

                        'App\\Http\\Requests\\StoreCafeteraRequest',
                        'App\\Http\\Requests\\StoreCafeteraRequest',
                        'App\\Http\\Resources\\CafeteraResource',
                        'App\\Http\\Resources\\CafeteraResource',
                        collect([new ModelAttributeConfig('title', new StringAttr())])
                    ),
                    new ControllerMethodConfig(
                        'destroy',
                        'delete',
                        collect([
                                    new ModelAttributeConfig('title', new StringAttr()),
                                    new ModelAttributeConfig('description', new StringAttr())
                                ]),

                        'App\\Http\\Requests\\StoreCafeteraRequest',
                        'App\\Http\\Requests\\StoreCafeteraRequest',
                        'App\\Http\\Resources\\CafeteraResource',
                        'App\\Http\\Resources\\CafeteraResource',
                        collect([new ModelAttributeConfig('title', new StringAttr())])
                    ),
                        new ControllerMethodConfig(
                            'show',
                            'get',
                            collect([
                                        new ModelAttributeConfig('title', new StringAttr()),
                                        new ModelAttributeConfig('description', new StringAttr())
                                    ]),

                            'App\\Http\\Requests\\StoreCafeteraRequest',
                            'App\\Http\\Requests\\StoreCafeteraRequest',
                            'App\\Http\\Resources\\CafeteraResource',
                            'App\\Http\\Resources\\CafeteraResource',
                            collect([new ModelAttributeConfig('title', new StringAttr())])
                        )
                ]),
    );

    $this->generator->create($this->simpleConfig);

    $file = PhpFile::fromCode(
        file_get_contents(base_path('src/Application/Cafetera/Controllers/CafeteraController.php'))
    );

    $class = $file->getClasses();

    $this->classFile = $class['Src\\Application\\Cafetera\\Controllers\\CafeteraController'];
});

afterEach(function () {
    File::deleteDirectory(base_path('src'));
});

it('creates a controller file')
    ->expect(fn() => file_exists(base_path('src/Application/Cafetera/Controllers/CafeteraController.php')))
    ->toBeTrue();

it('has correct namespace')
    ->expect(fn() => $this->classFile->getNamespace()->getName())
    ->toBe('Src\\Application\\Cafetera\\Controllers');

it('has correct class name')
    ->expect(fn() => $this->classFile->getName())
    ->toBe('CafeteraController');

it('has correct imports')
    ->expect(fn() => $this->classFile->getNamespace()->getUses())
    ->toBe([
               'StoreCafeteraRequest' => 'App\\Http\\Requests\\StoreCafeteraRequest',
               'CafeteraResource' => 'App\\Http\\Resources\\CafeteraResource',
               'Cafetera' => 'Src\\Domain\\Cafetera\\Models\\Cafetera',
           ]);



it('has correct methods', function () {
    expect($this->classFile->getMethods())->toHaveKeys(['store', 'index', 'show', 'update', 'destroy']);
});
