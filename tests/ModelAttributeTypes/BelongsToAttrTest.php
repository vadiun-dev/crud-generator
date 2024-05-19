<?php

use Hitocean\CrudGenerator\ModelAttributeTypes\BelongsToAttr;

beforeEach(function () {
    $this->attr = new BelongsToAttr('Src\Models\Client', 'clients', 'client');
});

it('needs model cast', function () {
    expect($this->attr->needsModelCast())->toBeFalse();
});

it('returns model cast', function () {
    $this->attr->modelCast();
})->throws(Exception::class, 'BelongsToAttr does not need a model cast.');

it('returns faker function', function () {

    expect($this->attr->fakerFunction())->toBe('Client::factory()->create()->id');
});

it('returns migration function', function () {

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('client_id', $this->attr, false);
    expect($this->attr->migrationFunction($config))->toBe("foreignId('client_id')->constrained('clients')");

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('client_id', $this->attr, true);
    expect($this->attr->migrationFunction($config))->toBe("foreignId('client_id')->constrained('clients')->nullable()");
});

it('returns data type', function () {

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('client_id', $this->attr, false);
    expect($this->attr->dataType($config))->toBe('int');

    $config = new \Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig('client_id', $this->attr, true);
    expect($this->attr->dataType($config))->toBe('?int');
});

it('needs import', function () {
    expect($this->attr->needsImport())->toBeTrue();
});

it('returns import path', function () {
    expect($this->attr->importPath())->toBe('Src\Models\Client');
});

it('returns related model class', function () {
    expect($this->attr->relatedModelClass())->toBe('Client');
});

it('returns relation name', function () {
    expect($this->attr->relationName())->toBe('client');
});
