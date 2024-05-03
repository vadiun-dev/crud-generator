<?php

namespace Hitocean\CrudGenerator\DTOs\Controller;

enum MethodName: string
{
    const INDEX = 'index';
    const SHOW = 'show';
    const STORE = 'store';
    const UPDATE = 'update';
    const DESTROY = 'destroy';
}
