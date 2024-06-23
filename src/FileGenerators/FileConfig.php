<?php

namespace Hitocean\CrudGenerator\FileGenerators;

interface FileConfig
{
    public function fileName(): string;

    public function filePath(): string;

    public function className(): string;

    public function namespace(): string;
}
