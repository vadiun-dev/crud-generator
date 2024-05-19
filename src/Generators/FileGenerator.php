<?php

namespace Hitocean\CrudGenerator\Generators;

use Illuminate\Filesystem\Filesystem;

abstract class FileGenerator
{

    abstract public function create($config): void;

    protected function createFile(string $path, string $file_content): void
    {
        $files = new Filesystem();
        $this->ensureDirectoryExistsForPath($path);
        $files->put($path, $file_content);
    }

    private function ensureDirectoryExistsForPath($path): void
    {
        $filesystem = new Filesystem();

        // Obtener el directorio padre del path proporcionado
        $directoryPath = dirname($path);

        // Verificar si el directorio ya existe
        if (! $filesystem->isDirectory($directoryPath)) {
            // Crear el directorio y todos los directorios necesarios
            $filesystem->makeDirectory($directoryPath, 0755, true);
        }
    }
}
