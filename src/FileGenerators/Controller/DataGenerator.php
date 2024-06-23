<?php

namespace Hitocean\CrudGenerator\FileGenerators\Controller;

use Hitocean\CrudGenerator\DTOs\Model\ModelAttributeConfig;
use Hitocean\CrudGenerator\FileGenerators\Controller\FileConfigs\DataConfig;
use Hitocean\CrudGenerator\FileGenerators\FileGenerator;
use Nette\PhpGenerator\PhpFile;

class DataGenerator extends FileGenerator
{
    /**
     * @param  DataConfig  $config
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
            if ($attr->type->needsImport()) {
                $namespace->addUse($attr->type->importPath());
                $property->setType($attr->type->importPath());
            } else {
                $property->setType($attr->type->dataType($attr));

            }
            if ($attr->type->needsDataAttribute()) {
                $property->addAttribute($attr->type->dataAttribute(), [$attr->type->dataAttributeParam()]);
            }
        }

        $this->createFile($config->filePath(), $file);

    }
}
