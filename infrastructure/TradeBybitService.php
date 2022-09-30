<?php

namespace infrastructure;

use infrastructure\Facades\ApiBybitFacade;
use infrastructure\Facades\TradeBybitFacade;
use App\Models\Trade;   
use App\Models\Symbol;  
use App\Models\Rate;  
use App\Models\Form_Detail;

class TradeBybitService{

    public function __construct()
    {
        $this->trade = new Trade();
        $this->rate = new Rate();
        $this->symbol = new Symbol();
        $this->form_detail = new Form_Detail();
    }

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
        $mark_price = $results[0]->mark_price;

        return $mark_price;      
    }

    //put trade
    public function createTrade($side,$symbol,$qty) //1
    {
        $url = 'http://localhost:8080/';
   
        $data = [
            'side'=>$side,
            'symbol'=>$symbol,
            'qty'=>$qty,
        ];

        $curl_url = $url."?".http_build_query($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $curl_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        return json_decode(curl_exec($curl));
   
    }



   // get data from table
    public function checkCondition()
    {
        $rows = $this->form_detail::getData();
    
        foreach($rows as $row){
            
            $id = $row->id;
            $price = $row->price;
            $symbol = $row->symbol;
            $leverage = $row->leverage;
            $repurchase = $row->repurchase;
            $side = $row->side;
            $qty = $row->qty;
         
            $mark_price = $this->getMarketPrice($symbol); 
            $this->checkMarkPrice($id,$mark_price,$side,$symbol,$qty,$price,$leverage,$repurchase);

        }
        
    }

 
    //check trade response 
    public function checkResponse($response,$id,$symbol,$repurchase,$leverage)
    {
        try {

            if($response != null){
                $this->trade::insertData($response,$repurchase,$leverage);
                $this->symbol::insertData($symbol);
                $this->rate::insertData($response->order_id,$repurchase);
                $this->form_detail::deleteData($id);
            }
          
          } catch (Exception $e) {
              return $e;
          
          }
        

    }

    //check price >= market price
    public function checkMarkPrice($id,$mark_price,$side,$symbol,$qty,$price,$leverage,$repurchase)
    { 

        if(($price >= $mark_price) && $side=="Sell"){
            $this->changeLeverage($symbol,$leverage);
            $response = $this->createTrade($side,$symbol,$qty)->result;
            dump($response);
            $this->checkResponse($response,$id,$symbol,$repurchase,$leverage);
            
        }
        if($price <= $mark_price && $side=="Buy"){
            $this->changeLeverage($symbol,$leverage);
            $response = $this->createTrade($side,$symbol,$qty)->result;
            dump($response);
            $this->checkResponse($response,$id,$symbol,$repurchase,$leverage);

        }
    }

    //get trading list
    public function getTradeList()
    {
        $orders = $this->trade::getData();  

        foreach($orders as $order){
            $id = $order->id;
            $entry_price =  $order->price;
            $side = $order->side;
            $leverage = $order->leverage;
            $qty = $order->qty;
            $symbol = $order->symbol;

            $mark_prices = $this->getMarketPrice($symbol); 

            if($entry_price!=0){

                if($side == "Buy"){
                
                    $position_unrealized = $qty * (1/$entry_price - 1/$mark_prices);
                    $initial_margin = $qty/($entry_price * $leverage);
                    $bankruptcy_price = $entry_price * ($leverage/($leverage+1));
                    $fee_to_close = ($qty/$bankruptcy_price) * 0.0006;
                    $position_margin = $initial_margin + $fee_to_close;
                    $pnl = ($position_unrealized/$position_margin)*100;
                    dump($pnl);

                    if($pnl < -5){

                        $this->trade::updateLeverage($id); 
                        //set leverage
                        $url = 'http://localhost:8081/';
                
                        $data = [
                            'symbol'=>$symbol,

                        ];

                        $curl = curl_init();
                        
                        $curl_url = $url."?".http_build_query($data);
            
                        
                        curl_setopt($curl, CURLOPT_URL, $curl_url);
                        curl_setopt($curl, CURLOPT_POST, true);
                        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_exec($curl);
                
                    }

                    if($pnl > 5){
                
                        //close trade
                        $url = 'http://localhost:8082/';
                
                        $data = [
                            'side'=>'Sell',
                            'symbol'=>$symbol,
                            'qty'=>$order->size,
                        ];
            
                        $curl = curl_init();
                        $curl_url = $url."?".http_build_query($data);
            
                        
                        curl_setopt($curl, CURLOPT_URL, $curl_url);
                        curl_setopt($curl, CURLOPT_POST, true);
                        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $res = curl_exec($curl);
                        dump($res);
                        if($res->ret_code == 0){
                            $this->trade::updateStatus($id);
                        }
                        

                    }

                    if($pnl>=$order->repurchase){ 

                        $status = $this->createTrade($side,$symbol,$order->qty);

                        if($status->ret_code == 0){
                            $response_two = $status->result;
                            $this->trade::insertData($response_two,$repurchase,$leverage);
                            return false;
                        }  
                        
                        return true;
                        
                    }
  
                }
                
    
                if($side == "Sell"){

                    $position_unrealized = $qty * (1/$mark_prices - 1/$entry_price);
                    $initial_margin = $qty/($entry_price * $leverage);
                    $bankruptcy_price = $entry_price * ($leverage/($leverage-1));
                    $fee_to_close = ($qty/$bankruptcy_price) * 0.0006;
                    $position_margin = $initial_margin+$fee_to_close;
                    $pnl = ($position_unrealized/$position_margin)*100;


                    if($pnl < -5){

                        $this->trade::updateLeverage($id); 
                        
                    //set leverage
                        $url = 'http://localhost:8081/';
                
                        $data = [
                            'symbol'=>$symbol,
                            'buy_leverage'=>5,
                            'sell_leverage'=>5,
                        ];
            
                        $curl_url = $url."?".http_build_query($data);
            
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $curl_url);
                        curl_setopt($curl, CURLOPT_POST, true);
                        //  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                        curl_exec($curl);
                
                }

                    if($pnl > 5){
                        //close trade
                        $url = 'http://localhost:8082/';
                
                        $data = [
                            'side'=>'Buy',
                            'symbol'=>$symbol,
                            'qty'=>$order->size,
                        ];
            
                        $curl_url = $url."?".http_build_query($data);

                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $curl_url);
                        curl_setopt($curl, CURLOPT_POST, true);
                        //  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $res = curl_exec($curl);
                        dump($res);
                        if($res->ret_code == 0){
                            $this->trade::updateStatus($id);
                        }

                    }      
                    
                    if($pnl>=$order->repurchase){ 

                        $status = $this->createTrade($side,$symbol,$order->qty);

                        if($status->ret_code == 0){
                            $response_two = $status->result;
                            $this->trade::insertData($response_two,$repurchase,$leverage);
                            return false;
                        }  
                        
                        return true;
                        
                          
                    }
                        
                }
            }
            
        }    
        
    }
}