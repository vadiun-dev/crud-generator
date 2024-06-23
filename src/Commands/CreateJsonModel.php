<?php

namespace Hitocean\CrudGenerator\Commands;

use Hitocean\CrudGenerator\Helpers\ModelHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class CreateJsonModel extends Command
{
    protected $signature = 'make:hit-model-config {modelName?}';

    protected $description = 'Genera un archivo JSON en /generators/models';

    public function handle(ModelHelper $helper): void
    {
        $allModels = $helper::getAllModels();
        $modelName = $this->argument('modelName');

        while (! $modelName) {
            $modelName = text(
                label: '¿Cuál es el nombre del modelo?',
                required: true
            );
        }

        // Pedir nombre de la carpeta con el valor por defecto del nombre del modelo
        $folderName = text(
            label: '¿Cuál es el nombre de la carpeta?',
            default: $modelName,
            required: true
        );

        // Asegurar que el prefijo 'src/Domain' esté presente en root_folder
        $rootFolder = 'src/Domain/'.ltrim($folderName, '/').'/Models';

        // Generar root_namespace basado en rootFolder
        $rootNamespace = 'Src\\Domain\\'.str_replace('/', '\\', $folderName).'\\Models';

        $attributes = $this->collectAttributes($allModels);

        $data = [
            'modelName' => $modelName,
            'root_folder' => str_replace('\\', '/', $rootFolder),
            'root_namespace' => $rootNamespace,
            'tableName' => Str::of($modelName)->plural()->lower()->snake()->toString(),
            'crud' => true,
            'attributes' => $attributes,
        ];

        $directory = base_path('generators/models');

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = $directory.'/'.$modelName.'.json';

        File::put($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info("Archivo JSON para el modelo {$modelName} generado en {$filePath}");
    }

    protected function collectAttributes(Collection $allModels): array
    {
        $this->info('Definición de atributos del modelo:');

        $attributes = [];
        while (true) {
            $name = text(
                label: 'Nombre del atributo (o presiona Enter para terminar)',
                required: false
            );

            if (empty($name)) {
                break;
            }

            $type = select(
                'Tipo del atributo',
                ['int', 'string', 'float', 'bool', 'date', 'relation'],
                'string'
            );

            if ($type === 'relation') {

                $modelChoice = select(
                    label: 'Seleccione el modelo relacionado',
                    options: $allModels->pluck('name', 'import')->toArray()
                );

                $relatedModel = $allModels->firstWhere('import', $modelChoice);
                $modelImport = $relatedModel['import'];
                $table = $relatedModel['table'];
                $relationName = Str::of($relatedModel['name'])->camel()->lower()->toString();

                $attributes[] = [
                    'name' => $name,
                    'type' => 'belongsTo',
                    'model_import' => $modelImport,
                    'table' => $table,
                    'relation_name' => $relationName,
                ];
            } else {
                $optional = confirm('¿El atributo es opcional?', false);

                if ($optional) {
                    $type = '?'.$type;
                }

                $attributes[] = ['name' => $name, 'type' => $type];
            }
        }

        return $attributes;
    }
}
