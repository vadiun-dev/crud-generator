<?php

use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttr;

it('needs model cast', function () {
    $booleanAttr = new StringAttr();
    expect($booleanAttr->needsModelCast())->toBeFalse();
});

it('returns model cast', function () {
    $booleanAttr = new StringAttr();
    $booleanAttr->modelCast();
})->throws(Exception::class, 'StringAttr does not need a model cast.');

it('returns faker function', function () {
    $booleanAttr = new StringAttr();
    expect($booleanAttr->fakerFunction())->toBe('$this->faker->word');
});

it('returns migration function', function () {
    $booleanAttr = new StringAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->migrationFunction($config))->toBe("string('name')");

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->migrationFunction($config))->toBe("string('name')->nullable()");
});

it('needs resource type', function () {
    $booleanAttr = new StringAttr();
    expect($booleanAttr->needsResourceMap())->toBeFalse();
});

it('resource map property', function () {
    $booleanAttr = new StringAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->resourceMapProperty($config))->toBe('name');

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->resourceMapProperty($config))->toBe('name');
});

it('returns resource type', function () {
    $booleanAttr = new StringAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->resourceType($config))->toBe('string');

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->resourceType($config))->toBe('?string');
});
