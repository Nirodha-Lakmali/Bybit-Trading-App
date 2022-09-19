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
        'symbol','leverage','side','qty','price'
    ];

    static function getData()
    {
        $row = DB::table('trades')->select('id','symbol', 'price','qty','side')->get();
        return $row;
    }

    static function deleteTrade($id)
    {
        DB::table('trades')->where('id', $id)->delete();
        
    }

 
}
