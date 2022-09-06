<?php

namespace infrastructure\Facades;

use Illuminate\Support\Facades\Facade;

class TradeBybitFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'infrastructure\TradeBybitService';
    }

}