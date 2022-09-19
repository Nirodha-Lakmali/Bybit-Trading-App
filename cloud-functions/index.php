<?php

use Google\CloudFunctions\FunctionsFramework;
use Psr\Http\Message\ServerRequestInterface;

FunctionsFramework::http('openTrade', 'openTrade');
FunctionsFramework::http('setLeverage', 'setLeverage');
FunctionsFramework::http('closeTrading', 'closeTrading');

require "api-connection.php";

function openTrade(ServerRequestInterface $request): string
{ 
    $side ='';
    $symbol = '';
    $qty = '';
    //$price = '';
    
    $queryString = $request->getQueryParams();

    $side = $queryString['side'] ?? $side;
    $symbol = $queryString['symbol'] ?? $symbol;
    $qty = $queryString['qty'] ?? $qty;
  //  $price = $queryString['price'] ?? $price;
    
    $url = 'https://api-testnet.bybit.com/private/linear/order/create';
    $method = 'POST';

    $params = [
        'side'=>$side,
        'symbol'=>$symbol,
        'order_type'=>'Market',
        'qty'=>$qty,
      //  'price'=>$price,
        'time_in_force'=>'GoodTillCancel',
        'reduce_only' =>false,
        'close_on_trigger'=>false,
        'timestamp' => time() * 1000,
    ];

    return setApi($method,$params,$url);

        
}


function setLeverage(ServerRequestInterface $request): string
{

    $symbol = '';
    $queryString = $request->getQueryParams();
    $symbol = $queryString['symbol'] ?? $symbol;

    $url = 'https://api-testnet.bybit.com/private/linear/position/set-leverage';
    $params = [
        'symbol'=>$symbol,
        'buy_leverage'=>5,
        'sell_leverage'=>5,
        'timestamp' => time() * 1000,
    ];
        
    $method = 'POST';

    return setApi($method,$params,$url);
    
}


function closeTrading(ServerRequestInterface $request): string
{
    $symbol = '';
    $side ='';
    $qty = '';

    $queryString = $request->getQueryParams();
    $symbol = $queryString['symbol'] ?? $symbol;
    $side = $queryString['side'] ?? $side;
    $qty = $queryString['qty'] ?? $qty;

    $url = 'https://api-testnet.bybit.com/private/linear/order/create';
    $method = 'POST';

    $params = [
        'side'=>$side,
        'symbol'=>$symbol,
        'order_type'=>'Market',
        'qty'=>$qty,
        'time_in_force'=>'GoodTillCancel',
        'reduce_only' =>true,
        'close_on_trigger'=>true,
        'timestamp' => time() * 1000,
    ];

    return setApi($method,$params,$url);

}