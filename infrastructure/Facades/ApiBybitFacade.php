<?php

namespace infrastructure\Facades;

use Illuminate\Support\Facades\Facade;

class ApiBybitFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'infrastructure\ApiBybitService';
    }

}