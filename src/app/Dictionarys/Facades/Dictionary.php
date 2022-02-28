<?php

namespace VCComponent\Laravel\Dictionary\Dictionarys\Facades;

use Illuminate\Support\Facades\Facade;

class Dictionary extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dictionary';
    }
}
