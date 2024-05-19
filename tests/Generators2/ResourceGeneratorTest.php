<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\Generators\ControllerGenerator;
use Hitocean\CrudGenerator\Generators\ResourceGenerator;
use Hitocean\CrudGenerator\Generators\FileConfigs\ControllerConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ResourceConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ModelConfig;
use Hitocean\CrudGenerator\Generators\ModelGenerator;
use Hitocean\CrudGenerator\ModelAttributeTypes\BooleanAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\FloatAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\IntAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\ModelAttributeType;
use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttr;

beforeEach(function ()
{
    $this->generator = new ResourceGenerator();

    $this->simpleModelConfig = new ResourceConfig(
        'StoreClient', 'src', 'Src', collect([
                                                                      new ModelAttributeConfig(
                                                                          'first_name',
                                                                          new StringAttr(),
                                                                          false
                                                                      ),
                                                                  ])
    );

    $this->generator->create($this->simpleModelConfig);

    $file  = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('src/Resources/StoreClientResource.php')));
    $class = $file->getClasses();

    $this->classFile = $class['Src\Resources\StoreClientResource'];
});

afterEach(function ()
{
    unlink(base_path('src/Resources/StoreClientResource.php'));
});

it('creates a data file', function ()
{
    expect(file_exists(base_path('src/Resources/StoreClientResource.php')))->toBeTrue();
});

it('has correct namespace', function ()
{
    expect($this->classFile->getNamespace()->getName())->toBe('Src\Resources');
});

it('has correct class name', function ()
{
    expect($this->classFile->getName())->toBe('StoreClientResource');
});

it('has correct imports', function(ModelAttributeType $attribute, $import, $use){
    $config = new ResourceConfig(
        'StoreClient2', 'src', 'Src', collect([
                                                 new ModelAttributeConfig(
                                                     'first_name',
                                                     $attribute,
                                                     false
                                                 ),
                                             ])
    );

    $this->generator->create($config);

    $file  = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('src/Resources/StoreClient2Resource.php')));
    $classFile = $file->getClasses()['Src\Resources\StoreClient2Resource'];

    if(is_null($import)){
        expect($classFile->getNamespace()->getUses())->toBe(['Data' => 'Spatie\LaravelData\Data']);

    } else {
        expect($classFile->getNamespace()->getUses())->toBe([$import => $use,  'Data' => 'Spatie\LaravelData\Data']);
    }

})->with([
    [new DateTimeAttr(), 'Carbon', 'Carbon\Carbon'],
    [new StringAttr(), null, null],
    [new IntAttr(), null, null],
    [new FloatAttr(), null, null],
    [new BooleanAttr(), null, null],
         ]);

it('has correct properties', function ()
{
    expect($this->classFile->getProperties())->toHaveCount(1)
        ->and($this->classFile->getProperties()['first_name']->getName())->toBe('first_name')
        ->and($this->classFile->getProperties()['first_name']->getType())->toBe('string')
        ->and($this->classFile->getProperties()['first_name']->getVisibility())->toBe('public');
});
