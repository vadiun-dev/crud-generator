<?php

use Hitocean\CrudGenerator\Helpers\ModelHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

use function Pest\Laravel\artisan;
use function Pest\Laravel\mock;
use function PHPUnit\Framework\assertFileExists;

function mockModelHelper($properties)
{
    $fake_model_collection = new Collection([
        [
            'name' => 'User',
            'import' => 'Src\\Domain\\User\\Models\\User',
            'properties' => new Collection($properties),
        ],
    ]);

    mock(ModelHelper::class, function ($mock) use ($fake_model_collection) {
        $mock->shouldReceive('getAllModels')->andReturn($fake_model_collection);
    });
}

afterEach(function () {
    File::deleteDirectory(base_path('generators'));

});

beforeEach(function (){

    $this->method_options =  [
                                  'destroy',
                                  'index',
                                  'show',
                                  'store',
                                  'update'

    ];
});

it('detiene la ejecución si el controlador ya existe', function () {

    File::partialMock()
        ->shouldReceive('exists')
        ->with(base_path('src/Application/Test/Controllers/TestController.php'))
        ->andReturn(true);

    artisan('make:hit-controller-config', ['controllerName' => 'TestController'])
        ->expectsQuestion('¿Cuál es el nombre de la carpeta?', 'Test')
        ->expectsOutput('El archivo '.base_path('src/Application/Test/Controllers/TestController.php').' ya existe. Deteniendo la ejecución.')
        ->assertExitCode(1)
        ->assertFailed();

})->throws(\Exception::class);

it('prompts for the controller name if not provided', function (string $controller_name) {
    mockModelHelper([['name' => 'name', 'type' => 'string']]);

    artisan('make:hit-controller-config')
        ->expectsQuestion('¿Cuál es el nombre del controlador?', $controller_name)
        ->expectsQuestion('¿Cuál es el nombre de la carpeta?', 'User')
        ->expectsQuestion('Seleccione el modelo correspondiente al controlador', 'Src\\Domain\\User\\Models\\User')
        ->expectsQuestion('¿Desea utilizar todas las propiedades del modelo?', true)
        ->expectsQuestion('¿Desea agregar atributos adicionales?', false)
        ->expectsChoice('Seleccione los métodos a generar', ['index', 'show', 'store', 'update', 'destroy'], $this->method_options)

        ->assertExitCode(0);

    $jsonContent = json_decode(File::get(base_path('generators/controllers/UserController.json')), true);

    expect($jsonContent)->toMatchArray(['controller_name' => 'UserController']);
})->with([
    ['UserController'],
    ['User'],
    ['user'],
    ['userController'],
]);

it('prompts for the folder name', function (string $folder_name, string $path, string $test_path) {
    mockModelHelper([['name' => 'name', 'type' => 'string']]);

    artisan('make:hit-controller-config', ['controllerName' => 'TestController'])
        ->expectsQuestion('¿Cuál es el nombre de la carpeta?', $folder_name)
        ->expectsQuestion('Seleccione el modelo correspondiente al controlador', 'Src\\Domain\\User\\Models\\User')
        ->expectsQuestion('¿Desea utilizar todas las propiedades del modelo?', true)
        ->expectsQuestion('¿Desea agregar atributos adicionales?', false)
        ->expectsChoice('Seleccione los métodos a generar', ['index', 'show', 'store', 'update', 'destroy'], $this->method_options)
        ->assertExitCode(0);

    $jsonContent = json_decode(File::get(base_path('generators/controllers/TestController.json')), true);

    expect($jsonContent)->toMatchArray(['root_folder' => $path, 'test_path' => $test_path]);

})->with([
    ['Test', 'src/Application/Test/Controllers', 'tests/Application/Test/Controllers/TestControllerTest'],
    ['test', 'src/Application/Test/Controllers', 'tests/Application/Test/Controllers/TestControllerTest'],
    ['Tests/Pruebas', 'src/Application/Tests/Pruebas/Controllers', 'tests/Application/Tests/Pruebas/Controllers/TestControllerTest'],
]);

it('it selects all attributes from model', function () {
    mockModelHelper([
        [
            'name' => 'name',
            'type' => 'string',
        ], [
            'name' => 'size',
            'type' => 'string',
        ], [
            'name' => 'id',
            'type' => 'string',
        ],
    ]);

    artisan('make:hit-controller-config')
        ->expectsQuestion('¿Cuál es el nombre del controlador?', 'User')
        ->expectsQuestion('¿Cuál es el nombre de la carpeta?', 'User')
        ->expectsQuestion('Seleccione el modelo correspondiente al controlador', 'Src\\Domain\\User\\Models\\User')
        ->expectsQuestion('¿Desea utilizar todas las propiedades del modelo?', true)
        ->expectsQuestion('¿Desea agregar atributos adicionales?', false)
        ->expectsChoice('Seleccione los métodos a generar', ['index'], $this->method_options)
        ->assertExitCode(0);

    $jsonContent = json_decode(File::get(base_path('generators/controllers/UserController.json')), true);

    assertFileExists(base_path('generators/controllers/UserController.json'));
    expect($jsonContent)->toMatchArray([
        'methods' => [
            [
                'name' => 'index',
                'route_method' => 'get',
                'data_class_import' => null,
                'data_class_path' => null,
                'resource_class_import' => 'Src\\Application\\User\\Resources\\IndexUserResource',
                'resource_class_path' => 'src/Application/User/Resources/IndexUserResource',
                'inputs' => [],
                'outputs' => [
                    [
                        'name' => 'name',
                        'type' => 'string',
                    ], [
                        'name' => 'size',
                        'type' => 'string',
                    ], [
                        'name' => 'id',
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ]);

});

it('it selects specifc attributes from model', function () {

    mockModelHelper([
        [
            'name' => 'name',
            'type' => 'string',
        ], [
            'name' => 'size',
            'type' => 'string',
        ], [
            'name' => 'id',
            'type' => 'string',
        ],
    ]);

    artisan('make:hit-controller-config')
        ->expectsQuestion('¿Cuál es el nombre del controlador?', 'User')
        ->expectsQuestion('¿Cuál es el nombre de la carpeta?', 'User')
        ->expectsQuestion('Seleccione el modelo correspondiente al controlador', 'Src\\Domain\\User\\Models\\User')
        ->expectsQuestion('¿Desea utilizar todas las propiedades del modelo?', false)
        ->expectsChoice('Seleccione las propiedades a utilizar', ['id', 'name'], ['id', 'id', 'name', 'name', 'size', 'size'])
        ->expectsQuestion('¿Desea agregar atributos adicionales?', false)
        ->expectsChoice('Seleccione los métodos a generar', ['index'], $this->method_options)
        ->assertExitCode(0);

    $jsonContent = json_decode(File::get(base_path('generators/controllers/UserController.json')), true);

    assertFileExists(base_path('generators/controllers/UserController.json'));
    expect($jsonContent)->toMatchArray([
        'controller_name' => 'UserController',
        'root_folder' => 'src/Application/User/Controllers',
        'root_namespace' => 'Src\\Application\\User\\Controllers',
        'model_import' => 'Src\\Domain\\User\\Models\\User',
        'methods' => [
            [
                'name' => 'index',
                'route_method' => 'get',
                'data_class_import' => null,
                'data_class_path' => null,
                'resource_class_import' => 'Src\\Application\\User\\Resources\\IndexUserResource',
                'resource_class_path' => 'src/Application/User/Resources/IndexUserResource',
                'inputs' => [],
                'outputs' => [

                    [
                        'name' => 'name',
                        'type' => 'string',
                    ],
                    [
                        'name' => 'id',
                        'type' => 'string',
                    ],
                ],
            ],
        ],
    ]);

});

it('it generates attributes correctly', function (string $type, string $optional, string $result_type) {

    mockModelHelper([['name' => 'name', 'type' => 'string']]);

    artisan('make:hit-controller-config')
        ->expectsQuestion('¿Cuál es el nombre del controlador?', 'User')
        ->expectsQuestion('¿Cuál es el nombre de la carpeta?', 'User')
        ->expectsQuestion('Seleccione el modelo correspondiente al controlador', 'Src\\Domain\\User\\Models\\User')
        ->expectsQuestion('¿Desea utilizar todas las propiedades del modelo?', true)
        ->expectsQuestion('¿Desea agregar atributos adicionales?', true)
        ->expectsQuestion('Nombre del atributo adicional (o presiona Enter para terminar)', 'name')
        ->expectsQuestion('Tipo del atributo', $type)
        ->expectsConfirmation('¿El atributo es opcional?', $optional)
        ->expectsQuestion('Nombre del atributo adicional (o presiona Enter para terminar)', '')
        ->expectsChoice('Seleccione los métodos a generar', ['index'], $this->method_options)
        ->assertExitCode(0);

    $jsonContent = json_decode(File::get(base_path('generators/controllers/UserController.json')), true);

    assertFileExists(base_path('generators/controllers/UserController.json'));
    expect($jsonContent)->toMatchArray([
        'controller_name' => 'UserController',
        'root_folder' => 'src/Application/User/Controllers',
        'root_namespace' => 'Src\\Application\\User\\Controllers',
        'model_import' => 'Src\\Domain\\User\\Models\\User',
        'methods' => [
            [
                'name' => 'index',
                'route_method' => 'get',
                'data_class_import' => null,
                'data_class_path' => null,
                'resource_class_import' => 'Src\\Application\\User\\Resources\\IndexUserResource',
                'resource_class_path' => 'src/Application/User/Resources/IndexUserResource',
                'inputs' => [],
                'outputs' => [
                    [
                        'name' => 'name',
                        'type' => 'string',
                    ],
                    [
                        'name' => 'name',
                        'type' => $result_type,
                    ],
                ],
            ],
        ],
    ]);

})->with([
    ['string', 'no', 'string'],
    ['string', 'yes', '?string'],
    ['int', 'no', 'int'],
    ['int', 'yes', '?int'],
    ['float', 'no', 'float'],
    ['float', 'yes', '?float'],
    ['bool', 'no', 'bool'],
    ['bool', 'yes', '?bool'],
    ['date', 'no', 'date'],
    ['date', 'yes', '?date'],
]);

it('ask for methods', function (string $method_name, string $route_method, ?string $data_import, ?string $data_path, ?string $resource_import, ?string $resource_path, array $inputs, array $outputs) {
    mockModelHelper([
        [
            'name' => 'name',
            'type' => 'string',
        ],
    ]);

    artisan('make:hit-controller-config')
        ->expectsQuestion('¿Cuál es el nombre del controlador?', 'User')
        ->expectsQuestion('¿Cuál es el nombre de la carpeta?', 'User')
        ->expectsQuestion('Seleccione el modelo correspondiente al controlador', 'Src\\Domain\\User\\Models\\User')
        ->expectsQuestion('¿Desea utilizar todas las propiedades del modelo?', false)
        ->expectsChoice('Seleccione las propiedades a utilizar', ['id', 'name'], [ 'name', 'name'])
        ->expectsQuestion('¿Desea agregar atributos adicionales?', false)
        ->expectsChoice('Seleccione los métodos a generar', [$method_name], $this->method_options)
        ->assertExitCode(0);

    $jsonContent = json_decode(File::get(base_path('generators/controllers/UserController.json')), true);

    assertFileExists(base_path('generators/controllers/UserController.json'));
    expect($jsonContent)->toMatchArray([
        'controller_name' => 'UserController',
        'root_folder' => 'src/Application/User/Controllers',
        'root_namespace' => 'Src\\Application\\User\\Controllers',
        'model_import' => 'Src\\Domain\\User\\Models\\User',
        'methods' => [
            [
                'name' => $method_name,
                'route_method' => $route_method,
                'data_class_import' => $data_import,
                'data_class_path' => $data_path,
                'resource_class_import' => $resource_import,
                'resource_class_path' => $resource_path,
                'inputs' => $inputs,
                'outputs' => $outputs,
            ],
        ],
    ]);
})->with([
    ['index', 'get', null, null, 'Src\\Application\\User\\Resources\\IndexUserResource', 'src/Application/User/Resources/IndexUserResource', [], [['name' => 'name', 'type' => 'string']]],
    ['show', 'get', null, null, 'Src\\Application\\User\\Resources\\ShowUserResource', 'src/Application/User/Resources/ShowUserResource', [], [['name' => 'name', 'type' => 'string']]],
    ['store', 'post', 'Src\\Application\\User\\Data\\StoreUserData', 'src/Application/User/Data/StoreUserData', null, null, [['name' => 'name', 'type' => 'string']], []],
    ['update', 'put', 'Src\\Application\\User\\Data\\UpdateUserData', 'src/Application/User/Data/UpdateUserData', null, null, [['name' => 'name', 'type' => 'string']], []],
    ['destroy', 'delete', null, null, null, null, [], []],
]);
