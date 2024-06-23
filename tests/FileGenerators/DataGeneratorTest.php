<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\DataGenerator;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\DataConfig;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BelongsToAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\DateTimeAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\FloatAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\IntAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\ModelAttributeType;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->generator = new DataGenerator();

    $this->simpleModelConfig = new DataConfig(
        'StoreClient', 'src', 'Src', collect([
            new ModelAttributeConfig(
                'first_name',
                new StringAttr(),
                false
            ),
        ])
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
    $config = new DataConfig(
        'StoreClient2', 'src', 'Src', collect([
            new ModelAttributeConfig(
                'first_name',
                $attribute,
                false
            ),
        ])
    );

    $this->generator->create($config);

    $file = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('src/StoreClient2.php')));
    $classFile = $file->getClasses()['Src\StoreClient2'];

    if (is_null($import)) {
        expect($classFile->getNamespace()->getUses())->toBe(['Data' => 'Spatie\LaravelData\Data']);

    } else {
        expect($classFile->getNamespace()->getUses())->toEqual([$import => $use,  'Data' => 'Spatie\LaravelData\Data']);
    }

})->with([
    [new DateTimeAttr(), 'Carbon', 'Carbon\Carbon'],
    [new StringAttr(), null, null],
    [new IntAttr(), null, null],
    [new FloatAttr(), null, null],
    [new BooleanAttr(), null, null],
    [new BelongsToAttr('Src\Models\Product', 'products', 'product'), 'Product', 'Src\Models\Product'],
]);

it('has correct properties', function () {
    expect($this->classFile->getProperties())->toHaveCount(1)
        ->and($this->classFile->getProperties()['first_name']->getName())->toBe('first_name')
        ->and($this->classFile->getProperties()['first_name']->getType())->toBe('string')
        ->and($this->classFile->getProperties()['first_name']->getVisibility())->toBe('public');
});
