<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\ResourceConfig;
use Hitocean\CrudGenerator\FileGenerators\FileGenerator;
use Nette\PhpGenerator\PhpFile;

class ResourceGenerator extends FileGenerator
{
    /**
     * @param  ResourceConfig  $config
     */
    public function create($config): void
    {
        $file = new PhpFile();

        $spatie_import = 'Spatie\LaravelData\Data';
        $namespace = $file->addNamespace($config->namespace())
            ->addUse($spatie_import)
            ->addUse($config->model_import);

        $class = $namespace->addClass($config->className())
            ->setExtends($spatie_import);


        $contrusct = $class->addMethod('__construct');
        /** @var ModelAttributeConfig $attr */
        foreach ($config->attributes as $attr) {

            $property = $contrusct->addPromotedParameter($attr->name)->setVisibility('public');
            if($attr->type->needsImport()){

                $namespace->addUse($attr->type->importPath());
            }
                $property->setType($attr->type->resourceType($attr));
        }

        if($config->attributes->filter(fn($attr) => $attr->type->needsResourceMap()))
        {
            $method = $class->addMethod('fromModel')
                            ->setReturnType('self')
                            ->setStatic()
                            ->setVisibility('public');

            $method->addParameter('model')->setType($config->model_import);

            $method->addBody('return new self(')
                ->addBody($config->attributes->map(fn(ModelAttributeConfig $attr) => "\$model->{$attr->type->resourceMapProperty($attr)},")->join("\n"))
                ->addBody(');')
            ;
        }

        $this->createFile($config->filePath(), $file);

    }
}
