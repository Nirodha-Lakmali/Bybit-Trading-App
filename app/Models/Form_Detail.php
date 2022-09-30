<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form_Detail extends Model
{
    use HasFactory;
    protected $table = 'form_details';

    protected $fillable = [
        'symbol','side','qty','price','leverage','repurchase'
    ];

    static function insertData($list){

        self::create((array)$list);
    }

    static function getData()
    {
        $row = self::select('id','symbol','price','qty','side','leverage','repurchase')->get();
        return $row;
    }

    static function deleteData($id)
    {
        self::where('id', $id)->delete();
        
    }

}
