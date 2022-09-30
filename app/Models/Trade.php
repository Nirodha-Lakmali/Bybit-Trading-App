<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Trade extends Model
{
    use HasFactory;
    protected $table = 'trades';

    protected $fillable = [
        'order_id','symbol','side','qty','order_type','price','repurchase','leverage','order_status'
    ];

    static function insertData($list,$repurchase,$leverage){

        $list->repurchase = $repurchase;
        $list->leverage = $leverage;
        //$new_row = array_push((array)$list,$repurchase);
        self::create((array)$list);
    }

    static function getData()
    {
        $row = DB::table('trades')->select('id','order_id','symbol','side','qty','order_type','price','repurchase','leverage','order_status')->get();
        return $row;
    }

    static function updateLeverage($id){
        DB::table('trades')->where('id', $id)->update(['leverage' => 5]);
    }

    static function updateStatus($id,$status){
        DB::table('trades')->where('id', $id)->update(['order_status' => "closed"]);
    }

    static function deleteTrade($id)
    {
        DB::table('trades')->where('id', $id)->delete();
        
    }

 
}
