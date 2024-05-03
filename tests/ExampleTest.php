<?php

it('can test', function () {
    //dd(__DIR__.'/..');
    $config = \Hitocean\CrudGenerator\CrudGenerator::handle()[0];

    $config = new \Hitocean\CrudGenerator\DTOs\Controller\ControllerConfig('CafeteraController', $config, 'src/controllers', collect([]), true);
    //    $factory_creator = new \Hitocean\CrudGenerator\Generators\FactoryGenerator();
    //    $model_creator = new \Hitocean\CrudGenerator\Generators\ModelGenerator();
       # $migration_generator = new \Hitocean\CrudGenerator\Generators\MigrationGenerator();
    //    $factory_creator->create($config);
    //    $model_creator->create($config);
     #   $migration_generator->create($config);
    $controller_generator = new \Hitocean\CrudGenerator\Generators\ControllerGenerator();
    $controller_generator->create($config);


    expect(true)->toBeTrue();
});
