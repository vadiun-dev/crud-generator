<?php

it('needs model cast', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr();
    expect($booleanAttr->needsModelCast())->toBeTrue();
});

it('returns model cast', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr();
    expect($booleanAttr->modelCast())->toBe('datetime');
});

it('returns faker function', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr();
    expect($booleanAttr->fakerFunction())->toBe('$this->faker->dateTime');
});

it('returns migration function', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->migrationFunction($config))->toBe("dateTime('name')");

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->migrationFunction($config))->toBe("dateTime('name')->nullable()");
});

it('needs resource map', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr();
    expect($booleanAttr->needsResourceMap())->toBeTrue();
});

it('returns resource map property', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->resourceMapProperty($config))->toBe('name->toDateTimeString()');

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->resourceMapProperty($config))->toBe('name->toDateTimeString()');
});

it('return resource type', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->resourceType($config))->toBe('string');

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->resourceType($config))->toBe('?string');
});
