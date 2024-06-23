<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerMethodConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ModelControllerConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest\ModelControllerTestGenerator;
use Hitocean\CrudGenerator\FileGenerators\Controller\ModelControllerTest\ModelControllerTestStoreMethod;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->generator = new ModelControllerTestGenerator();

    $this->simpleModelConfig = new ModelControllerConfig(
        'CafeteraController',
        'Src\\Domain\\Cafetera\\Models\\Cafetera',
        'src/Application/Cafetera/Controllers',
        'Src\\Application\\Cafetera\\Controllers',
        'tests/Application/Cafetera/Controllers/CafeteraControllerTest',
        collect([
                    new ControllerMethodConfig(
                        'index',
                        'get',
                        collect(),
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
                    )
                    ,
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
                    ,
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
                    )
                ]),
    );

    $this->generator->create($this->simpleModelConfig);


    $file  = Nette\PhpGenerator\PhpFile::fromCode(
        file_get_contents(base_path('Application/Cafetera/Controllers/CafeteraControllerTest.php'))
    );

    $class = $file->getClasses();

    $this->classFile = $class['Tests\Application\Cafetera\Controllers\CafeteraControllerTest'];
});

afterEach(function () {
    File::deleteDirectory(base_path('tests'));
    File::deleteDirectory(base_path('Application'));
});

it('creates a controller file')
    ->expect(fn() => file_exists(base_path('Application/Cafetera/Controllers/CafeteraControllerTest.php')))
                               ->toBeTrue();

it('has correct namespace')
    ->expect(fn() => $this->classFile->getNamespace()->getName())
    ->toBe('Tests\Application\Cafetera\Controllers');

it('has correct class name')
    ->expect(fn() => $this->classFile->getName())
    ->toBe('CafeteraControllerTest');


it('has correct imports')
    ->expect(fn() => $this->classFile->getNamespace()->getUses())
    ->toBe([
        'CafeteraController' => 'Src\Application\Cafetera\Controllers\CafeteraController',
        'Cafetera' => 'Src\Domain\Cafetera\Models\Cafetera',
        'TestCase' => 'Tests\TestCase',
    ]);


it('has correct methods', function () {
    expect($this->classFile->getMethods())
        ->toHaveKeys(['it_update', 'it_destroy', 'it_show', 'it_store', 'it_index']);
});

