<?php

use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\BooleanAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\DateTimeAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\FloatAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\IntAttr;
use Hitocean\CrudGenerator\FileGenerators\ModelAttributeTypes\StringAttr;
use Hitocean\CrudGenerator\ModelConfigFactory;

it('validates data structure', function () {
    $data = [
        'modelName' => 'Client',
        'root_folder' => 'Admin/Client',
        'root_namespace' => 'Admin\Client',
        'attributes' => [
            ['name' => 'srl', 'type' => 'int'],
            ['name' => 'num_client_seidor', 'type' => 'int'],
            ['name' => 'name', 'type' => 'string'],
            ['name' => 'cuit', 'type' => 'string'],
        ],
        'tableName' => 'clients',
    ];

    $modelConfig = ModelConfigFactory::makeConfig($data);

    expect($modelConfig->modelName)->toBe('Client');
    expect($modelConfig->root_folder)->toBe('Admin/Client');
    expect($modelConfig->attributes->count())->toBe(4);
    expect($modelConfig->tableName)->toBe('clients');
});

it('throws exception when missing keys', function () {
    $data = [
        'attributes' => [
            ['name' => 'srl', 'type' => 'int'],
        ],
    ];

    ModelConfigFactory::makeConfig($data);
})->throws(\Exception::class, 'Missing keys: modelName, root_folder, root_namespace, tableName, makeCrud');

it(' throws exception when attributes is not an array', function () {
    $data = [
        'modelName' => 'Client',
        'root_folder' => 'Admin/Client',
        'root_namespace' => 'Admin\Client',
        'attributes' => 'not an array',
        'tableName' => 'clients',
        'makeCrud' => true,
    ];

    ModelConfigFactory::makeConfig($data);
})->throws(\Exception::class, 'Attributes must be an array');

it('throws exception when attribute name or type is missing', function ($attribute) {
    $data = [
        'modelName' => 'Client',
        'root_folder' => 'Admin/Client',
        'root_namespace' => 'Admin\Client',
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
        'root_folder' => 'Admin/Client',
        'root_namespace' => 'Admin\Client',
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
        'root_folder' => 'Admin/Client',
        'root_namespace' => 'Admin\Client',
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
