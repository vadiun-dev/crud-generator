<?php

it('can test', function () {
    //dd(__DIR__.'/..');
    $config = \Hitocean\CrudGenerator\CrudGenerator::handle()[0];

//    $factory_creator = new \Hitocean\CrudGenerator\Generators\FactoryGenerator();
//    $model_creator = new \Hitocean\CrudGenerator\Generators\ModelGenerator();
//    $migration_generator = new \Hitocean\CrudGenerator\Generators\MigrationGenerator();
//    $factory_creator->create($config);
//    $model_creator->create($config);
//    $migration_generator->create($config);

    $file = Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path('src/Cafetera/Models/Cafetera.php')));
    $class = $file->getClasses();

    if($class['Src\Cafetera\Cafetera'] instanceof \Nette\PhpGenerator\ClassType)
    {
        $method = $class['Src\Cafetera\Cafetera']->getProperty('fillable');
    }
    expect(true)->toBeTrue();
});
