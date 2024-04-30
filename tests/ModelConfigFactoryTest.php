<?php

use Hitocean\CrudGenerator\ModelAttributeTypes\BooleanAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\DateTimeAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\FloatAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\IntAttr;
use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttr;
use Hitocean\CrudGenerator\ModelConfigFactory;

it('validates data structure', function () {
    $data = [
        'modelName' => 'Client',
        'folder' => 'Admin/Client',
        'attributes' => [
            ['name' => 'srl', 'type' => 'int'],
            ['name' => 'num_client_seidor', 'type' => 'int'],
            ['name' => 'name', 'type' => 'string'],
            ['name' => 'cuit', 'type' => 'string'],
        ],
        'tableName' => 'clients',
        'makeCrud' => true,
    ];

    $modelConfig = ModelConfigFactory::makeConfig($data);

    expect($modelConfig->modelName)->toBe('Client');
    expect($modelConfig->folder)->toBe('Admin/Client');
    expect($modelConfig->attributes->count())->toBe(4);
    expect($modelConfig->tableName)->toBe('clients');
    expect($modelConfig->has_abm)->toBeTrue();
});

it('throws exception when missing keys', function () {
    $data = [
        'attributes' => [
            ['name' => 'srl', 'type' => 'int'],
            ['name' => 'num_client_seidor', 'type' => 'int'],
            ['name' => 'name', 'type' => 'string'],
            ['name' => 'cuit', 'type' => 'string'],
            ['name' => 'phone', 'type' => 'string'],
            ['name' => 'live_release_date', 'type' => 'datetime'],

        ],
    ];

    ModelConfigFactory::makeConfig($data);
})->throws(\Exception::class, 'Missing keys: modelName, folder, tableName, makeCrud');

it(' throws exception when attributes is not an array', function () {
    $data = [
        'modelName' => 'Client',
        'folder' => 'Admin/Client',
        'attributes' => 'not an array',
        'tableName' => 'clients',
        'makeCrud' => true,
    ];

    ModelConfigFactory::makeConfig($data);
})->throws(\Exception::class, 'Attributes must be an array');

it('throws exception when attribute name is missing', function ($attribute) {
    $data = [
        'modelName' => 'Client',
        'folder' => 'Admin/Client',
        'attributes' => [
            $attribute,
        ],
        'tableName' => 'clients',
        'makeCrud' => true,
    ];

    ModelConfigFactory::makeConfig($data);
})->with([
    [['name' => 'first_name']],
    [['type' => 'string']],
    [[]],
])
    ->throws(\Exception::class, 'Every attribute must have a name and a type');

it('maps attributes types', function ($type, $expected) {

    $data = [
        'modelName' => 'Client',
        'folder' => 'Admin/Client',
        'attributes' => [
            ['name' => 'srl', 'type' => $type],
        ],
        'tableName' => 'clients',
        'makeCrud' => true,
    ];

    $modelConfig = ModelConfigFactory::makeConfig($data);

    expect($modelConfig->attributes->first()->type)->toBeInstanceOf($expected);
})->with([
    ['integer', IntAttr::class],
    ['int', IntAttr::class],
    ['string', StringAttr::class],
    ['boolean', BooleanAttr::class],
    ['bool', BooleanAttr::class],
    ['datetime', DateTimeAttr::class],
    ['float', FloatAttr::class],
]);

it('maps optional attributes types', function ($type, $expected) {

    $data = [
        'modelName' => 'Client',
        'folder' => 'Admin/Client',
        'attributes' => [
            ['name' => 'srl', 'type' => $type.'?'],
            ['name' => 'srl', 'type' => '?'.$type],
        ],
        'tableName' => 'clients',
        'makeCrud' => true,
    ];

    $modelConfig = ModelConfigFactory::makeConfig($data);

    expect($modelConfig->attributes->first()->type)->toBeInstanceOf($expected);
    expect($modelConfig->attributes[1]->type)->toBeInstanceOf($expected);
})->with([
    ['integer', IntAttr::class],
    ['int', IntAttr::class],
    ['string', StringAttr::class],
    ['boolean', BooleanAttr::class],
    ['bool', BooleanAttr::class],
    ['datetime', DateTimeAttr::class],
    ['float', FloatAttr::class],
]);
