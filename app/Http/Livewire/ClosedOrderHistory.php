<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Trade;   

class ClosedOrderHistory extends Component
{
    public function render()
    {
        $orders = Trade::getData();
        
        return view('livewire.closed-order-history',['orders'=>$orders]);
    }
}
