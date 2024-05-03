<?php

it('needs model cast', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\ModelAttributeTypes\BooleanAttr();
    expect($booleanAttr->needsModelCast())->toBeTrue();
});

it('returns model cast', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\ModelAttributeTypes\BooleanAttr();
    expect($booleanAttr->modelCast())->toBe('bool');
});

it('returns faker function', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\ModelAttributeTypes\BooleanAttr();
    expect($booleanAttr->fakerFunction())->toBe('boolean');
});

it('returns migration function', function () {
    $booleanAttr = new \Hitocean\CrudGenerator\ModelAttributeTypes\BooleanAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->migrationFunction($config))->toBe("boolean('name')");

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->migrationFunction($config))->toBe("boolean('name')->nullable()");
});

