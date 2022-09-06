<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;    
use infrastructure\Facades\ApiBybitFacade;
use infrastructure\Facades\TradeBybitFacade;

class ApiController extends Controller
{
 
    public function openTrade(Request $request)
    {
        $key = $request->currency;
        $leverage = $request->leverage;
        $side = $request->side;
        $qty = $request->quantity;
        $price  = $request->price;
        
        $mark_prices = TradeBybitFacade::getMarketPrice();
        $mark_price = $mark_prices[$key]->mark_price;
 
        $symbol = TradeBybitFacade::getSymbol($key);
        TradeBybitFacade::changeLeverage($symbol,$leverage);

        if($price >= $mark_price){
            TradeBybitFacade::trade($side,$symbol,$qty,$price);
        }
     
        return redirect('home');
        
    }
    
}
