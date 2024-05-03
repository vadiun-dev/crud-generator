<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\DTOs\Model\ModelConfig;
use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttr;
use Nette\PhpGenerator\Literal;

beforeEach(function () {
    $this->generator = new \Hitocean\CrudGenerator\Generators\FactoryGenerator();
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

    $file = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('database/factories/ClientFactory.php')));
    $class = $file->getClasses();

    $this->classFile = $class['Database\Factories\ClientFactory'];
});

afterEach(function () {
    unlink(base_path('database/factories/ClientFactory.php'));
});

it('creates a factory file', function () {
    expect(file_exists(base_path('database/factories/ClientFactory.php')))->toBeTrue();
});

it('has correct namespace', function () {
    expect($this->classFile->getNamespace()->getName())->toBe('Database\Factories');
});

it('has correct class name', function () {
    expect($this->classFile->getName())->toBe('ClientFactory');
});

it('it extends from factory', function () {
    expect($this->classFile->getExtends())->toBe('Illuminate\Database\Eloquent\Factories\Factory');
});

it('has correct properties', function () {
    expect($this->classFile->getProperties())->toHaveCount(1)
        ->and($this->classFile->getProperties())->toHaveKey('model')
        ->and($this->classFile->getProperties()['model']->getValue())->toEqual(new Literal('/*(n*/\Src\Client\Models\Client::class'));
});

it('has correct methods', function () {
    expect($this->classFile->getMethods())->toHaveCount(1)
        ->toHaveKey('definition')
        ->and($this->classFile->getMethods()['definition']->getBody())->toContain('return [')
        ->toContain("'first_name' => \$this->faker->word,")
        ->toContain('];')
        ->and($this->classFile->getMethods()['definition']->getVisibility())->toBe('public');
});
