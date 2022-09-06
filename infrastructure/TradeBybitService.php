<?php

namespace infrastructure;

use infrastructure\Facades\ApiBybitFacade;

class TradeBybitService{

    public function getList()
    {
        $url = 'https://api-testnet.bybit.com/v2/public/symbols';
        $params = [];
        $method = 'GET';
        $currency_pairs = ApiBybitFacade::getApi($method,$params,$url);
       
        return $currency_pairs->result;
        
    }

    public function getSymbol($key)
    {
        $currencies = $this->getList();
        $symbol  = $currencies[$key]->name;   
        return $symbol;      
    }


    public function changeLeverage($symbol,$leverage)
    {
        $url = 'https://api-testnet.bybit.com/v2/private/position/leverage/save';
        $params = [
            'symbol'=>$symbol,
            'leverage'=>$leverage
        ];

        $method = 'POST';
        $currency_pair = ApiBybitFacade::getApi($method,$params,$url);
        return $currency_pair;
    }


    public function getMarketPrice()
    {
        $url = 'https://api-testnet.bybit.com/v2/public/tickers';
        $params = [];
        $method = 'GET';
        $market_price = ApiBybitFacade::getApi($method,$params,$url)->result;
       
        return $market_price;      
    }

    public function trade($side,$symbol,$qty,$price)
    {
        $url = 'https://api-testnet.bybit.com/private/linear/order/create';
        $params = [
            'side'=>$side,
            'symbol'=>$symbol,
            'order_type'=>'Limit',
            'qty'=>$qty,
            'price'=>$price,
            'time_in_force'=>'GoodTillCancel',
            'reduce_only' =>false,
            'close_on_trigger'=>false,


        ];
        $method = 'POST';
        $order = ApiBybitFacade::getApi($method,$params,$url);

        return $order;
    }



}