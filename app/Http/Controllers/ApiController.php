<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use infrastructure\Facades\ApiBybitFacade;
use infrastructure\Facades\TradeBybitFacade;
use App\Models\Trade;   
use App\Models\Symbol;
use App\Models\Form_Detail;

class ApiController extends Controller
{

    //trade form data submit
    public function openTrade(Request $request)
    {
        Form_Detail::insertData($request->all());

        return redirect('home')->with('success','Trade Data successfully inserted!');
        
    }

    public function getData()
    {
        TradeBybitFacade::getTradeList();
        return view('welcome');
    }

}
