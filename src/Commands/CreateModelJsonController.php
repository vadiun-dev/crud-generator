<?php

namespace Hitocean\CrudGenerator\Commands;

use Exception;
use Hitocean\CrudGenerator\Helpers\ModelHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use function base_path;
use function in_array;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function ltrim;

class CreateModelJsonController extends Command
{
    protected $signature = 'make:hit-controller-config {controllerName?}';

    protected $description = 'Genera un archivo JSON en /generators/controllers';

    public function handle(ModelHelper $helper): void
    {
        $allModels = $helper::getAllModels();

        $controllerName = $this->askControllerName();
        $folderName = $this->askFolderName($controllerName);
        $model = $this->askModel($allModels);
        $modelAttributes = $this->askModelAttributes($model['properties']);
        $additionalAttributes = $this->askAdditionalAttributes($allModels);

        $methods = $this->askMethods($folderName, $modelAttributes, $additionalAttributes, $model['name']);

        $data = [
            'controller_name' => $controllerName,
            'root_folder' => "src/Application/$folderName/Controllers",
            'root_namespace' => 'Src\\Application\\'.str_replace('/', '\\', $folderName).'\\Controllers',
            'model_import' => $model['import'],
            'test_path' => "tests/Application/{$folderName}/Controllers/{$controllerName}Test",
            'methods' => $methods,
        ];

        $this->createFile($controllerName, $data);

    }

    protected function askMethods(string $folderName, Collection $modelAttributes, array $additionalAttributes, string $model_name): array
    {
        $methods = [];

        $methodChoices = multiselect(
            label: 'Seleccione los métodos a generar',
            options: ['index', 'store', 'update', 'destroy', 'show']
        );

        foreach ($methodChoices as $methodName) {
            $methods[] = [
                'name' => $methodName,
                'route_method' => $this->getRouteMethod($methodName),
                'data_class_import' => $this->getDataClassImport($folderName, $methodName, $model_name),
                'data_class_path' => $this->getDataClassPath($folderName, $methodName, $model_name),
                'resource_class_import' => $this->getResourceClassImport($folderName, $methodName, $model_name),
                'resource_class_path' => $this->getResourceClassPath($folderName, $methodName, $model_name),
                'inputs' => $this->getRouteMethod($methodName) === 'post' || $this->getRouteMethod($methodName) === 'put' ? $modelAttributes->merge($additionalAttributes) : [],
                'outputs' => $this->getRouteMethod($methodName) === 'get' ? $modelAttributes->merge($additionalAttributes) : [],
            ];
        }

        return $methods;
    }

    private function getRouteMethod(string $methodName): string
    {
        return match ($methodName) {
            'index', 'show' => 'get',
            'store' => 'post',
            'update' => 'put',
            'destroy' => 'delete',
            default => 'get',
        };
    }

    private function getDataClassImport(string $folderName, string $methodName, string $model_name): ?string
    {
        $dataClasses = ['store', 'update'];
        $_folderName = str_replace('/', '\\', $folderName);
        if (in_array($methodName, $dataClasses)) {
            return "Src\\Application\\{$_folderName}\\Data\\".ucfirst($methodName).ucfirst($model_name).'Data';
        }

        return null;
    }

    private function getDataClassPath(string $folderName, string $methodName, string $model_name): ?string
    {
        $dataClasses = ['store', 'update'];
        if (in_array($methodName, $dataClasses)) {
            return "src/Application/{$folderName}/Data/".ucfirst($methodName).ucfirst($model_name).'Data';
        }

        return null;
    }

    private function getResourceClassImport(string $folderName, string $methodName, string $model_name): ?string
    {
        $_folderName = str_replace('/', '\\', $folderName);

        if (in_array($methodName, ['index', 'show'])) {
            return "Src\\Application\\{$_folderName}\\Resources\\".ucfirst($methodName).ucfirst($model_name).'Resource';
        }

        return null;
    }

    private function getResourceClassPath(string $folderName, string $methodName, string $model_name): ?string
    {
        if (in_array($methodName, ['index', 'show'])) {
            return "src/Application/{$folderName}/Resources/".ucfirst($methodName).ucfirst($model_name).'Resource';
        }

        return null;
    }

    private function createFile(string $controllerName, array $data): void
    {
        $directory = base_path('generators/controllers');

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = $directory.'/'.$controllerName.'.json';

        File::put($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info("Archivo JSON para el controlador {$controllerName} generado en {$filePath}");
    }

    private function askFolderName(string $controllerName): string
    {
        $defaultFolderName = Str::replaceLast('Controller', '', $controllerName);

        $folderName = text(
            label: '¿Cuál es el nombre de la carpeta?',
            default: $defaultFolderName,
            required: true
        );

        // Verificar si el archivo del controlador ya existe y detener la ejecución si es así
        $controllerPath = base_path('src/Application/'.ltrim(ucfirst($folderName), '/')."/Controllers/{$controllerName}.php");
        if (File::exists($controllerPath)) {
            $this->error("El archivo {$controllerPath} ya existe. Deteniendo la ejecución.");
            throw new Exception("El archivo {$controllerPath} ya existe.");
        }

        return ucfirst(ltrim($folderName, '/'));
    }

    public function askControllerName(): string
    {
        $controllerName = $this->argument('controllerName');

        if (! $controllerName) {
            $controllerName = text(
                label: '¿Cuál es el nombre del controlador?',
                required: true
            );
        }

        if (! Str::endsWith($controllerName, 'Controller')) {
            $controllerName .= 'Controller';
        }

        return ucfirst($controllerName);
    }

    public function askModel(Collection $allModels)
    {
        $modelChoice = select(
            label: 'Seleccione el modelo correspondiente al controlador',
            options: $allModels->pluck('name', 'import')->toArray()
        );

        return $allModels->firstWhere('import', $modelChoice);

    }

    public function askModelAttributes(Collection $properties): Collection
    {
        $useAllProperties = confirm('¿Desea utilizar todas las propiedades del modelo?', false);

        if (! $useAllProperties) {
            $selectedAttributes = multiselect(
                label: 'Seleccione las propiedades a utilizar',
                options: $properties->pluck('name', 'name')->toArray()
            );

            return $properties->whereIn('name', $selectedAttributes)->values();
        }

        return $properties;
    }

    protected function askAdditionalAttributes(Collection $allModels): array
    {
        $attributes = [];

        if (! confirm('¿Desea agregar atributos adicionales?', false)) {
            return $attributes;
        }

        $this->info('Definición de atributos adicionales del controlador:');

        while (true) {
            $name = text(
                label: 'Nombre del atributo adicional (o presiona Enter para terminar)',
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
