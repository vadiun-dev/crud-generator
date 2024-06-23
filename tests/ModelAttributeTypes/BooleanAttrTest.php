<?php

it('needs model cast', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr();
    expect($booleanAttr->needsModelCast())->toBeTrue();
});

it('returns model cast', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr();
    expect($booleanAttr->modelCast())->toBe('bool');
});

it('returns faker function', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr();
    expect($booleanAttr->fakerFunction())->toBe('$this->faker->boolean');
});

it('returns migration function', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->migrationFunction($config))->toBe("boolean('name')");

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->migrationFunction($config))->toBe("boolean('name')->nullable()");
});

it('needs resource map', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr();
    expect($booleanAttr->needsResourceMap())->toBeFalse();
});

it('returns resource map property', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->resourceMapProperty($config))->toBe('name');

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->resourceMapProperty($config))->toBe('name');
});

it('returns resource type', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->resourceType($config))->toBe('bool');

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->resourceType($config))->toBe('?bool');
});
