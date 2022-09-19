<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use infrastructure\Facades\ApiBybitFacade;
use infrastructure\Facades\TradeBybitFacade;
use App\Models\Trade;   
use App\Models\Symbol;

class ApiController extends Controller
{

    public function openTrade(Request $request)
    {
        $symbol = $request->symbol;
        $leverage = $request->leverage;
        Trade::create($request->all());
        TradeBybitFacade::changeLeverage($symbol,$leverage);
     
        return redirect('home')->with('success','Trade Data successfully inserted!');
        
    }


}
