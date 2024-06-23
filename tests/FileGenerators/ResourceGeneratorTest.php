<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ResourceConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\ResourceGenerator;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\DateTimeAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\FloatAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\IntAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\ModelAttributeType;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->generator = new ResourceGenerator();

    $this->simpleModelConfig = new ResourceConfig(
        'StoreClient', 'src', 'Src', collect([

            new ModelAttributeConfig(
                'first_name',
                new StringAttr(),
                false
            ),
        ]), 'Src\Models\Client'
    );

    $this->generator->create($this->simpleModelConfig);

    $file = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('src/StoreClient.php')));
    $class = $file->getClasses();

    $this->classFile = $class['Src\StoreClient'];
});

afterEach(function () {
    File::deleteDirectory(base_path('src'));
});

it('creates a data file', function () {
    expect(file_exists(base_path('src/StoreClient.php')))->toBeTrue();
});

it('has correct namespace', function () {
    expect($this->classFile->getNamespace()->getName())->toBe('Src');
});

it('has correct class name', function () {
    expect($this->classFile->getName())->toBe('StoreClient');
});

it('has correct imports', function (ModelAttributeType $attribute, $import, $use) {
    $config = new ResourceConfig(
        'StoreClient2', 'src', 'Src', collect([

            new ModelAttributeConfig(
                'first_name',
                $attribute,
                false
            ),
        ]), 'Src\Models\Client2'
    );

    $this->generator->create($config);

    $file = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('src/StoreClient2.php')));
    $classFile = $file->getClasses()['Src\StoreClient2'];


    if (is_null($import)) {
        expect($classFile->getNamespace()->getUses())->toBe(['Data' => 'Spatie\LaravelData\Data', 'Client2' => 'Src\Models\Client2',]);

    } else {
        expect($classFile->getNamespace()->getUses())->toBe([$import => $use,  'Data' => 'Spatie\LaravelData\Data', 'Client2' => 'Src\Models\Client2']);
    }

})->with([
    [new DateTimeAttr(), 'Carbon', 'Carbon\Carbon'],
    [new StringAttr(), null, null],
    [new IntAttr(), null, null],
    [new FloatAttr(), null, null],
    [new BooleanAttr(), null, null],
]);


it('has correct properties', function () {
    $promoted_parameters = $this->classFile->getMethods()['__construct'];
    expect($promoted_parameters->getParameters())->toHaveCount(1)
        ->and($promoted_parameters->getParameters()['first_name']->getName())->toBe('first_name')
        ->and($promoted_parameters->getParameters()['first_name']->getType())->toBe('string')
        ->and($promoted_parameters->getParameters()['first_name']->getVisibility())->toBe('public');
});
