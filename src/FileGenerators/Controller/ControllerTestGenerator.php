<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller;

use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerConfig;
use Illuminate\Support\Facades\File;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;

class ControllerTestGenerator
{
    /**
     * Genera un archivo de test para el controlador basado en la configuración proporcionada.
     *
     * @param ControllerConfig $config
     * @return void
     */
    public function create(ControllerConfig $config): void
    {
        // Crear una nueva instancia de Nette\PhpGenerator\PhpFile
        $file = new PhpFile;

        // Agregar namespace
        $namespace = $file->addNamespace($config->root_namespace . '\\Tests');

        // Agregar la clase del test del controlador
        $className = $config->controller_name . 'Test';
        $class = $namespace->addClass($className)->setExtends('Tests\\TestCase');

        // Agregar los imports necesarios al namespace
        $namespace->addUse('Illuminate\\Http\\UploadedFile');
        $namespace->addUse($config->root_namespace . '\\' . $config->controller_name);
        $namespace->addUse('Illuminate\\Support\\Facades\\File');
        $namespace->addUse('Src\\Domain\\User\\Models\\User'); // asegurar que la clase User se importa correctamente
        $namespace->addUse('Src\\Domain\\User\\Enums\\Roles'); // asegurar que la clase Roles se importa correctamente

        // Agregar el método setUp al principio
        $setUpMethod = $class->addMethod('setUp')
                             ->setVisibility('public')
                             ->setReturnType('void')
                             ->addBody('parent::setUp();')
                             ->addBody('$user = User::factory()->withRole(Roles::CONTENTS)->create();')
                             ->addBody('$this->actingAs($user);');

        // Crear métodos para el test del controlador basados en $config->methods
        foreach ($config->methods as $methodConfig) {
            // Dinámicamente generar un nombre de prueba basado en el método del controlador
            $testMethod = $class->addMethod('it_' . $methodConfig->name)
                                ->setVisibility('public')
                                ->addComment('@test')
                                ->setReturnType('void');

            // Generar $data array si hay inputs
            if ($methodConfig->inputs->isNotEmpty()) {
                $testMethod->addBody('$data = [');
                foreach ($methodConfig->inputs as $input) {
                    $fakerMethod = $input->type->fakerTestFunction();
                    $testMethod->addBody("    '{$input->name}' => {$fakerMethod},");
                }
                $testMethod->addBody('];');
                $testMethod->addBody('');
            }

            $assertMethod = strtolower($methodConfig->route_method);
            $testMethod->addBody('$response = $this->' . $assertMethod . '(action([' . $config->controller_name . '::class, \'' . $methodConfig->name . '\']), $data ?? []);');
            $testMethod->addBody('$response->assertOk();');

            // Añadir $model y lógica de aserción si hay outputs
            if ($methodConfig->outputs->isNotEmpty()) {
                $testMethod->addBody('$response->assertExactJson([');
                foreach ($methodConfig->outputs as $output) {
                    $testMethod->addBody("    '{$output->name}' => \$model->{$output->name},");
                }
                $testMethod->addBody(']);');
            } else {
                $testMethod->addBody('// TODO: Implement assert logic for ' . $methodConfig->name);
            }
        }

        // Crear un printer para convertir el archivo PHP en una string
        $printer = new PsrPrinter;
        $output = $printer->printFile($file);

        // Asegurarse de que todos los directorios existan antes de escribir el archivo
        File::ensureDirectoryExists(base_path($config->test_folder), 0755, true);

        // Escribir el archivo utilizando Laravel's File facade
        File::put(base_path($config->test_folder . '/' . $className . '.php'), $output);
    }

    /**
     * Obtiene el método de Faker adecuado para el tipo de datos de entrada.
     *
     * @param string $type
     * @return string
     */
    private function getFakerMethod($input): string
    {
        return $input->type->fakerFunction();
    }
}
