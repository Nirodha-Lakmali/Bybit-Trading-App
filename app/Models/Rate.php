<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $table = 'rates';

    protected $fillable = ['order_id','repurchase'];

 
    static function insertData($order_id,$repurchase)
    {
        $data = self::create(['order_id'=>$order_id,'repurchase'=>$repurchase]);
        $data->save();
    }

    
    // static function getData($side,$symbol)
    // { 
    //     $row = self::where([
    //         ['side', $side],
    //         ['symbol', $symbol],
    //     ])->get();
    //     return $row;
    // }

    // static function deleteData($id)
    // {
    //     self::where('id', $id)->delete();
        
    // }
}
