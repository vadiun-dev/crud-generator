<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\Generators\ControllerGenerator;
use Hitocean\CrudGenerator\Generators\DataGenerator;
use Hitocean\CrudGenerator\Generators\FileConfigs\ControllerConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\DataConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ModelConfig;
use Hitocean\CrudGenerator\Generators\ModelGenerator;
use Hitocean\CrudGenerator\ModelAttributeTypes\BelongsToAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\BooleanAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\FloatAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\IntAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\ModelAttributeType;
use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttr;

beforeEach(function ()
{
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

    $file  = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('src/Data/StoreClientData.php')));
    $class = $file->getClasses();

    $this->classFile = $class['Src\Data\StoreClientData'];
});

afterEach(function ()
{
    unlink(base_path('src/Data/StoreClientData.php'));
});

it('creates a data file', function ()
{
    expect(file_exists(base_path('src/Data/StoreClientData.php')))->toBeTrue();
});

it('has correct namespace', function ()
{
    expect($this->classFile->getNamespace()->getName())->toBe('Src\Data');
});

it('has correct class name', function ()
{
    expect($this->classFile->getName())->toBe('StoreClientData');
});

it('has correct imports', function(ModelAttributeType $attribute, $import, $use){
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

    $file  = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('src/Data/StoreClient2Data.php')));
    $classFile = $file->getClasses()['Src\Data\StoreClient2Data'];

    if(is_null($import)){
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

it('has correct properties', function ()
{
    expect($this->classFile->getProperties())->toHaveCount(1)
        ->and($this->classFile->getProperties()['first_name']->getName())->toBe('first_name')
        ->and($this->classFile->getProperties()['first_name']->getType())->toBe('string')
        ->and($this->classFile->getProperties()['first_name']->getVisibility())->toBe('public');
});
