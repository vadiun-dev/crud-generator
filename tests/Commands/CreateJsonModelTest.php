<?php



use Hitocean\CrudGenerator\Commands\CreateJsonModel;
use Hitocean\CrudGenerator\Helpers\ModelHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Testing\TestCase;
use function Pest\Laravel\{mock, partialMock, fakeCommand, artisan};
use Illuminate\Support\Collection;
use function PHPUnit\Framework\assertFileEquals;
use function PHPUnit\Framework\assertFileExists;

afterEach(function() {
    // Simulate a clean environment before each test
    File::deleteDirectory(base_path('generators/models'));
    File::deleteDirectory(base_path('generators'));


});


it('prompts for the model name if not provided', function () {

    mock(ModelHelper::class, function ($mock) {
        $mock->shouldReceive('getAllModels')->andReturn(new Collection());
    });
  #  $mock = Mockery::mock('alias:'.ModelHelper::class);
  #  $mock->shouldReceive('getAllModels')->andReturn(new Collection());

    artisan('make:hit-model-config')
         ->expectsQuestion('¿Cuál es el nombre del modelo?', 'User')
        ->expectsQuestion('¿Cuál es el nombre de la carpeta?', 'User')
        ->expectsQuestion('Nombre del atributo (o presiona Enter para terminar)', '')
         ->assertExitCode(0);


    $jsonContent = json_decode(File::get(base_path('generators/models/User.json')), true);


    assertFileExists(base_path('generators/models/User.json'));
    expect($jsonContent)->toMatchArray([
        'modelName' => 'User',
        'root_folder' => 'src/Domain/User/Models',
        'root_namespace' => 'Src\\Domain\\User\\Models',
        'tableName' => 'users',
        'crud' => true,
        'attributes' => [],
    ]);
});


it('uses the provided model name argument', function () {
    artisan('make:hit-model-config', ['modelName' => 'User'])
        ->expectsQuestion('¿Cuál es el nombre de la carpeta?', 'User')
        ->expectsQuestion('Nombre del atributo (o presiona Enter para terminar)', '')
         ->assertExitCode(0);

    assertFileExists(base_path('generators/models/User.json'));

    $jsonContent = json_decode(File::get(base_path('generators/models/User.json')), true);

    expect($jsonContent)->toMatchArray([
                                           'modelName' => 'User',
                                           'root_folder' => 'src/Domain/User/Models',
                                           'root_namespace' => 'Src\\Domain\\User\\Models',
                                           'tableName' => 'users',
                                           'crud' => true,
                                           'attributes' => [],
                                       ]);
});

it('it generates attributes correctly', function (string $type, string $optional, string $result_type) {

    $this->artisan('make:hit-model-config', ['modelName' => 'User'])
         ->expectsQuestion('¿Cuál es el nombre de la carpeta?', 'User')
         ->expectsQuestion('Nombre del atributo (o presiona Enter para terminar)', 'name')
         ->expectsQuestion('Tipo del atributo', $type)
         ->expectsConfirmation('¿El atributo es opcional?', $optional)
         ->expectsQuestion('Nombre del atributo (o presiona Enter para terminar)', '')
         ->assertExitCode(0);

    $jsonContent = json_decode(File::get(base_path('generators/models/User.json')), true);

    expect($jsonContent)->toMatchArray([
                                           'modelName' => 'User',
                                           'root_folder' => 'src/Domain/User/Models',
                                           'root_namespace' => 'Src\\Domain\\User\\Models',
                                           'tableName' => 'users',
                                           'crud' => true,
                                           'attributes' => [
                                               ['name' => 'name', 'type' => $result_type],
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

it('creates the directory if it does not exist', function () {


    $directory = base_path('generators/models');

    // Cleanup - asegurar que el directorio no exista antes de probar
    if (File::exists($directory)) {
        File::deleteDirectory($directory);
    }
    mock(ModelHelper::class, function ($mock) {
        $mock->shouldReceive('getAllModels')->andReturn(new Collection());
    });

    artisan('make:hit-model-config')
        ->expectsQuestion('¿Cuál es el nombre del modelo?', 'User')
        ->expectsQuestion('¿Cuál es el nombre de la carpeta?', 'User')
        ->expectsQuestion('Nombre del atributo (o presiona Enter para terminar)', '')
        ->assertExitCode(0);

    assertFileExists(base_path('generators/models/User.json'));


});

