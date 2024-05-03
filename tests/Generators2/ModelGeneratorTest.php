<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\DTOs\Model\ModelConfig;
use Hitocean\CrudGenerator\Generators\ModelGenerator;
use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttr;

beforeEach(function () {
    $this->generator = new ModelGenerator();
    $this->simpleModelConfig = new ModelConfig(
        'Client', 'Client', collect([
            new ModelAttributeConfig(
                'first_name',
                new StringAttr(),
                false
            ),
        ]), 'clients', true
    );

    $this->generator->create($this->simpleModelConfig);

    $file = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('Client/Models/Client.php')));
    $class = $file->getClasses();

    $this->classFile = $class['Client\Models\Client'];
});

afterEach(function () {
    unlink(base_path('Client/Models/Client.php'));
});

it('creates a model file', function () {
    expect(file_exists(base_path('Client/Models/Client.php')))->toBeTrue();
});

it('has correct namespace', function () {
    expect($this->classFile->getNamespace()->getName())->toBe('Client\Models');
});

it('has correct class name', function () {
    expect($this->classFile->getName())->toBe('Client');
});

it('it extends from eloquent', function () {
    expect($this->classFile->getExtends())->toBe('Illuminate\Database\Eloquent\Model');
});

it('it has correct table name', function () {
    $property = $this->classFile->getProperty('table');

    expect($property->getValue())->toBe('clients')
        ->and($property->getVisibility())->toBe('protected');
});

it('it has correct fillable attributes', function () {
    $property = $this->classFile->getProperty('fillable');

    expect($property->getValue())->toBe(['first_name'])
        ->and($property->getVisibility())->toBe('protected');
});

it('it has correct casts', function () {
    $property = $this->classFile->getProperty('casts');

    expect($property->getValue())->toBe([])
        ->and($property->getVisibility())->toBe('protected');
});

it('has factory trait', function () {
    expect($this->classFile->getTraits())->toHaveKey('Illuminate\Database\Eloquent\Factories\HasFactory');
});
