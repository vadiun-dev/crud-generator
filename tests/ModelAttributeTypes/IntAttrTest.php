<?php

use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\IntAttr;

it('needs model cast', function () {
    $booleanAttr = new IntAttr();
    expect($booleanAttr->needsModelCast())->toBeFalse();
});

it('returns model cast', function () {
    $booleanAttr = new IntAttr();
    $booleanAttr->modelCast();
})->throws(Exception::class, 'IntAttr does not need a model cast.');

it('returns faker function', function () {
    $booleanAttr = new IntAttr();
    expect($booleanAttr->fakerFunction())->toBe('$this->faker->randomNumber(5)');
});

it('returns migration function', function () {
    $booleanAttr = new IntAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->migrationFunction($config))->toBe("integer('name')");

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->migrationFunction($config))->toBe("integer('name')->nullable()");
});

it('needs resource type', function () {
    $booleanAttr = new IntAttr();
    expect($booleanAttr->needsResourceMap())->toBeFalse();
});

it('resource map property', function () {
    $booleanAttr = new IntAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->resourceMapProperty($config))->toBe('name');

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->resourceMapProperty($config))->toBe('name');
});

it('returns resource type', function () {
    $booleanAttr = new IntAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->resourceType($config))->toBe('int');

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->resourceType($config))->toBe('?int');
});
