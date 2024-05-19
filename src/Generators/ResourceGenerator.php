<?php

namespace Hitocean\CrudGenerator\Generators;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ControllerConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\DataConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ModelConfig;
use Hitocean\CrudGenerator\Generators\FileConfigs\ResourceConfig;
use Hitocean\CrudGenerator\ModelAttributeTypes\ModelAttributeType;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;

class ResourceGenerator extends FileGenerator
{
    /**
     * @param ResourceConfig $config
     */
    public function create($config): void
    {
        $file = new PhpFile();

        $spatie_import = 'Spatie\LaravelData\Data';
        $namespace = $file->addNamespace($config->namespace())
            ->addUse($spatie_import);

        $class = $namespace->addClass($config->className())
                           ->setExtends($spatie_import);

        /** @var ModelAttributeConfig $attr */
        foreach ($config->attributes as $attr) {

            $property = $class->addProperty($attr->name)->setVisibility('public');
            if($attr->type->needsImport()){
                $namespace->addUse($attr->type->importPath());
                $property->setType($attr->type->importPath());
            } else {
                $property->setType($attr->type->dataType($attr));
            }
        }

        $this->createFile($config->filePath(), $file);

    }


}
