<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Model\FileConfigs\FactoryConfig;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BelongsToAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Illuminate\Support\Facades\File;
use Nette\PhpGenerator\Literal;

beforeEach(function () {
    $this->generator = new \Hitocean\CrudGenerator\FileGenerators\Model\FactoryGenerator();
    $this->simpleModelConfig = new FactoryConfig(
        collect([
            new ModelAttributeConfig(
                'first_name',
                new StringAttr(),
                false
            ),
        ]), 'Src\Client\Models\Client'
    );

    $this->generator->create($this->simpleModelConfig);

    $file = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('database/factories/ClientFactory.php')));
    $class = $file->getClasses();

    $this->classFile = $class['Database\Factories\ClientFactory'];
});

afterEach(function () {
    File::deleteDirectory(base_path('database'));
});

it('creates a factory file', function () {
    expect(file_exists(base_path('database/factories/ClientFactory.php')))->toBeTrue();
});

it('has correct namespace', function () {
    expect($this->classFile->getNamespace()->getName())->toBe('Database\Factories');
});

it('has correct imports', function () {
    expect($this->classFile->getNamespace()->getUses())
        ->toHaveCount(2)
        ->toBe([
            'Factory' => 'Illuminate\Database\Eloquent\Factories\Factory',
            'Client' => 'Src\Client\Models\Client',
        ]);
});

it('imports when relationship exists', function () {
    $config = new FactoryConfig(
        collect([
            new ModelAttributeConfig(
                'first_name',
                new StringAttr(),
                false
            ),
            new ModelAttributeConfig(
                'client',
                new BelongsToAttr('Src\Models\Product', 'products', 'product'),
                false
            ),
        ]), 'Src\Client\Models\Client'
    );

    $this->generator->create($config);

    $file = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('database/factories/ClientFactory.php')));
    $class = $file->getClasses();

    $classFile = $class['Database\Factories\ClientFactory'];

    expect($classFile->getNamespace()->getUses())
        ->toHaveCount(3)
        ->toBe([
            'Factory' => 'Illuminate\Database\Eloquent\Factories\Factory',
            'Client' => 'Src\Client\Models\Client',
            'Product' => 'Src\Models\Product',
        ]);
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
