<?php

use Hitocean\CrudGenerator\ModelAttributeTypes\FloatAttr;

it('needs model cast', function () {
    $booleanAttr = new FloatAttr();
    expect($booleanAttr->needsModelCast())->toBeFalse();
});

it('returns model cast', function () {
    $booleanAttr = new FloatAttr();
    $booleanAttr->modelCast();
})->throws(Exception::class, 'FloatAttr does not need a model cast.');

it('returns faker function', function () {
    $booleanAttr = new FloatAttr();
    expect($booleanAttr->fakerFunction())->toBe('randomFloat(2, 0, 100)');
});

it('returns migration function', function () {
    $booleanAttr = new FloatAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->migrationFunction($config))->toBe("float('name')");

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->migrationFunction($config))->toBe("float('name')->nullable()");
});
