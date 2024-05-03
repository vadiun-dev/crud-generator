<?php

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\DTOs\Model\ModelConfig;
use Hitocean\CrudGenerator\Generators\MigrationGenerator;
use Hitocean\CrudGenerator\ModelAttributeTypes\StringAttr;

beforeEach(function ()
{
    \Carbon\Carbon::setTestNow(\Carbon\Carbon::create(2024, 5, 1, 23, 22, 28));

    $this->generator         = new MigrationGenerator();
    $this->simpleModelConfig = new ModelConfig(
        'Client', 'Client', collect([
                                        new ModelAttributeConfig(
                                            'first_name',
                                            new StringAttr(),
                                            false
                                        ),
                                    ]), 'clients', true
    );

    $this->generator->create($this->simpleModelConfig);

    $this->classFile = file_get_contents(base_path('database/migrations/2024_05_01_232228_create_clients_table.php'));
});

afterEach(function () {
    unlink(base_path('database/migrations/2024_05_01_232228_create_clients_table.php'));
});

it('creates a migration file', function ()
{
    $file_name = now()->format('Y_m_d_His').'_create_clients_table';
    expect(file_exists(base_path("database/migrations/$file_name.php")))->toBeTrue();
});

it('has correct imports', function ()
{
    expect($this->classFile)->toContain('use Illuminate\Database\Migrations\Migration;')
        ->toContain('use Illuminate\Database\Schema\Blueprint;')
        ->toContain('use Illuminate\Support\Facades\Schema;');
});

it('has correct up method', function ()
{
    expect($this->classFile)->toContain('public function up(): void')
        ->toContain('Schema::create(\'clients\', function (Blueprint $table) {')
        ->toContain('$table->id();')
        ->toContain('$table->string(\'first_name\');')
        ->toContain('$table->timestamps();');
});

it('has correct down method', function ()
{
    expect($this->classFile)->toContain('public function down(): void')
        ->toContain('Schema::dropIfExists(\'clients\');');
});

