<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller;

use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ControllerConfig;
use Illuminate\Support\Facades\File;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;

class ControllerFileGenerator
{
    /**
     * Genera un archivo para el controlador basado en la configuración proporcionada.
     */
    public function create(ControllerConfig $config): void
    {
        // Crear una nueva instancia de Nette\PhpGenerator\PhpFile
        $file = new PhpFile;

        // Agregar namespace
        $namespace = $file->addNamespace($config->root_namespace);

        // Agregar la clase del controlador
        $class = $namespace->addClass($config->controller_name);

        // Variables para mantener un tracking de los imports para el archivo
        $imports = [];

        // Agregar métodos al controlador
        foreach ($config->methods as $methodConfig) {
            $method = $class->addMethod($methodConfig->name)
                ->setVisibility('public');

            // Agregar argumento data_class_import si no es null
            if ($methodConfig->data_class_import) {
                $method->addParameter('data')->setType($methodConfig->data_class_import);
                $imports[] = $methodConfig->data_class_import;
            }

            // Agregar contenido del método en caso de que resource_class_import no sea null
            if ($methodConfig->resource_class_import) {
                $resourceClass = $this->getShortClassName($methodConfig->resource_class_import);
                $method->addBody(sprintf('return %s::from($data);', $resourceClass));
                $imports[] = $methodConfig->resource_class_import;
            } else {
                $method->addBody('// TODO: Implement '.$methodConfig->name.' logic');
            }
        }

        // Agregar los imports al namespace
        $imports = array_unique($imports);
        foreach ($imports as $import) {
            $namespace->addUse($import);
        }

        $printer = new PsrPrinter;
        $output = $printer->printFile($file);

        File::ensureDirectoryExists(base_path($config->root_folder));

        File::put($config->filePath(), $output);
    }

    /**
     * Obtiene el nombre corto de una clase a partir de su nombre completamente cualificado.
     */
    private function getShortClassName(string $className): string
    {
        $parts = explode('\\', $className);

        return end($parts);
    }
}
