<?php

namespace infrastructure;

use infrastructure\Facades\CurrencyPairFacade;

class OpenTradeService{

    //open trade
    public function openTradeCondition($price,$qty,$symbol,$base_price)
    {
        $url = 'https://api-testnet.bybit.com/private/linear/stop-order/create';
        $params = [
            'symbol'=>$symbol,
            'leverage'=>$leverage
        ];
        $method = 'POST';
        $currency_pair = ApiBybitFacade::getApi($method,$params,$url);
        return $currency_pair;
        
    }


}