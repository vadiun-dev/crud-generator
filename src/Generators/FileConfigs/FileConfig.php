<?php

namespace Hitocean\CrudGenerator\Generators\FileConfigs;

interface FileConfig
{
    public function fileName(): string;
    public function filePath(): string;
    public function className(): string;

    public function namespace(): string;

}
