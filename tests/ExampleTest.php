<?php

it('can test', function () {
    //dd(__DIR__.'/..');
    $model_config = \Hitocean\CrudGenerator\CrudGenerator::handle()[0];

    $config = new \Hitocean\CrudGenerator\Generators\FileConfigs\ControllerConfig(
        'CafeteraController',
        $model_config->modelName,
        'Src\Models\Cafetera',
        $model_config->attributes,
        'src',
        'Src',
        collect([]),
    );
    //    $factory_creator = new \Hitocean\CrudGenerator\Generators\FactoryGenerator();
    $model_creator = new \Hitocean\CrudGenerator\Generators\ModelGenerator();
    // $migration_generator = new \Hitocean\CrudGenerator\Generators\MigrationGenerator();
    //    $factory_creator->create($config);
    //    $model_creator->create($config);
    //   $migration_generator->create($config);
    //$controller_generator = new \Hitocean\CrudGenerator\Generators\ControllerGenerator();
    //$controller_generator->create($config);
    //$dataGenerator = new \Hitocean\CrudGenerator\Generators\DataGenerator();
    //    $test_generator = new \Hitocean\CrudGenerator\Generators\ControllerTestGenerator();
    $model_creator->create($model_config);

    //$test_generator->create($test_config);

    expect(true)->toBeTrue();
});
