<?php

namespace infrastructure;

use infrastructure\Facades\ApiBybitFacade;
use infrastructure\Facades\TradeBybitFacade;
use App\Models\Trade;   
use App\Models\Symbol;  

class TradeBybitService{

    //get currency list
    public function getList()
    {
        $url = 'https://api-testnet.bybit.com/v2/public/tickers';
        $params = [];
        $method = 'GET';
        $currency_pairs = ApiBybitFacade::getApi($method,$params,$url)->result;
   
        return $currency_pairs;
        
    }

    //change leverage
    public function changeLeverage($symbol,$leverage)
    {
        $url = 'https://api-testnet.bybit.com/private/linear/position/set-leverage';
        $params = [
            'symbol'=>$symbol,
            'buy_leverage'=>$leverage,
            'sell_leverage'=>$leverage,
            'timestamp' => time() * 1000,
        ];
        
        $method = 'POST';
        $leverage = ApiBybitFacade::getApi($method,$params,$url);
        return $leverage;
    }

    //get market price
    public function getMarketPrice($symbol)
    {
        $url = 'https://api-testnet.bybit.com/v2/public/tickers';
        $params = [
            'symbol'=>$symbol
        ];
 
        $method = 'GET';
        $results = ApiBybitFacade::getApi($method,$params,$url)->result;

        return $results;      
    }

    //put trade
    public function trade($side,$symbol,$qty,$price) //1
    {
        $url = 'http://localhost:8080/';
   
        $data = [
            'side'=>$side,
            'symbol'=>$symbol,
            'qty'=>$qty,
            'price'=>$price,  
        ];

        $curl_url = $url."?".http_build_query($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $curl_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_exec($curl);
   
    }

    //check condition
    public function checkCondition()
    {
        $rows = Trade::getData()->toArray();
    
        foreach($rows as $row){
            
            $id = $row->id;
            $price = $row->price;
            $symbol = $row->symbol;
            $side = $row->side;
            $qty = $row->qty;
    
            $results = $this->getMarketPrice($symbol);             
            $mark_price = $results[0]->mark_price;       
            $this->checkMarkPrice($id,$mark_price,$side,$symbol,$qty,$price);

        }
        
    }

    //check price >= market price
    public function checkMarkPrice($id,$mark_price,$side,$symbol,$qty,$price)
    { 
   
        if($price >= $mark_price){
            $this->trade($side,$symbol,$qty,$price);
            Symbol::insertData($symbol);
            Trade::deleteTrade($id); 
           
            
            return "Trade is opened";
         
        }
        else{
            return "Trade is not opened";
        }
    }

    //get trading list
    public function getTradeList()
    {
        $symbols = Symbol::getData();
     
        foreach($symbols as $symbol){
            $params = [ 
                'symbol'=>$symbol['symbol'],
                'timestamp' => time() * 1000,            
            ];
        
            $url = 'https://api-testnet.bybit.com/private/linear/position/list';
            $method = 'GET';  
            $tradelist = ApiBybitFacade::getApi($method,$params,$url)->result;
            $mark_prices = $this->getMarketPrice($symbol['symbol']);   
           // $this->getPnl($tradelist,$mark_prices,$symbol['symbol']);
           if($tradelist){
                foreach($tradelist as $item){
                    $mark_price_value = $mark_prices[0]->mark_price;
                    $entry_price = $item->entry_price;
                    
                    if($entry_price!=0){
            
                        if($item->side == "Buy"){
                            //pnl = (close_price-entry_price)*100)/entry_price  
                           
                            $pnl = (($mark_price_value - $entry_price)*100)/$entry_price;
                            
                            if($pnl < -5){
                                //set leverage
                                $url = 'http://localhost:8081/';
                        
                                $data = [
                                    'symbol'=>$symbol['symbol'],
                                ];
                    
                                $curl_url = $url."?".http_build_query($data);
                    
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, $curl_url);
                                curl_setopt($curl, CURLOPT_POST, true);
                                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_exec($curl);
                        
                            }
                            if($pnl > 5){
                        
                                //close trade
                                $url = 'http://localhost:8082/';
                        
                                $data = [
                                    'side'=>$item->side,
                                    'symbol'=>$symbol['symbol'],
                                    'qty'=>$item->size,
                                ];
                    
                                $curl_url = $url."?".http_build_query($data);
                    
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, $curl_url);
                                curl_setopt($curl, CURLOPT_POST, true);
                                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_exec($curl);
                            }
                            //$this->checkPnl($pnl,$symbol,$item->side);
                        }
                        
            
                        if($item->side == "Sell"){
                            //pnl = (entry_price-close_price)*100)/entry_price
                            $pnl = (($entry_price - $mark_price_value)*100)/$entry_price; 
                            
                            if($pnl < -5){
                            //set leverage
                                $url = 'http://localhost:8081/';
                        
                                $data = [
                                    'symbol'=>$symbol['symbol'],
                                ];
                    
                                $curl_url = $url."?".http_build_query($data);
                    
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, $curl_url);
                                curl_setopt($curl, CURLOPT_POST, true);
                                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_exec($curl);
                        
                        }
                            if($pnl > 5){
                                //close trade
                                $url = 'http://localhost:8082/';
                        
                                $data = [
                                    'side'=>$item->side,
                                    'symbol'=>$symbol['symbol'],
                                    'qty'=>$item->size,
                                ];
                      
                                $curl_url = $url."?".http_build_query($data);
                    
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, $curl_url);
                                curl_setopt($curl, CURLOPT_POST, true);
                                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_exec($curl);

                            }         
                                
                        }
                    }
                    
                    
                } 
        }
        
        
         
           
    }
        
    }
}