<?php

use Hitocean\CrudGenerator\ModelAttributeTypes\IntAttr;

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
    expect($booleanAttr->fakerFunction())->toBe('randomNumber(5)');
});

it('returns migration function', function () {
    $booleanAttr = new IntAttr();

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, false);
    expect($booleanAttr->migrationFunction($config))->toBe("integer('name')");

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('name', $booleanAttr, true);
    expect($booleanAttr->migrationFunction($config))->toBe("integer('name')->nullable()");
});
