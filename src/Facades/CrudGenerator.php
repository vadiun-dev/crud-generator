<?php

namespace Hitocean\CrudGenerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hitocean\CrudGenerator\CrudGenerator
 */
class CrudGenerator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Hitocean\CrudGenerator\CrudGenerator::class;
    }
}
