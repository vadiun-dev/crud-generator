<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ResourceConfig;
use Hitocean\CrudGenerator\Generators\ResourceGenerator;
use Hitocean\CrudGenerator\ModelAttributeTypes\BooleanAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\FloatAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\IntAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\ModelAttributeType;
use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttr;

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

    $file = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('src/Resources/StoreClientResource.php')));
    $class = $file->getClasses();

    $this->classFile = $class['Src\Resources\StoreClientResource'];
});

afterEach(function () {
    unlink(base_path('src/Resources/StoreClientResource.php'));
});

it('creates a data file', function () {
    expect(file_exists(base_path('src/Resources/StoreClientResource.php')))->toBeTrue();
});

it('has correct namespace', function () {
    expect($this->classFile->getNamespace()->getName())->toBe('Src\Resources');
});

it('has correct class name', function () {
    expect($this->classFile->getName())->toBe('StoreClientResource');
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

    $file = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('src/Resources/StoreClient2Resource.php')));
    $classFile = $file->getClasses()['Src\Resources\StoreClient2Resource'];


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
