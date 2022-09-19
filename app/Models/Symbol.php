<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Symbol extends Model
{
    use HasFactory;
    protected $table = 'symbols';

    protected $fillable = ['symbol'];

 
    static function insertData($symbol)
    {
        $data = self::firstOrNew(['symbol'=>$symbol]);
        $data->save();
    }

    
    static function getData()
    {
        $row = self::all();
        return $row;
    }

 

}
