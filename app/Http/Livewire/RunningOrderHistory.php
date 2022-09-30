<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Trade;   

class RunningOrderHistory extends Component
{
    public function render()
    {
        $orders = Trade::getData();
        return view('livewire.running-order-history',['orders'=>$orders]);
    }
}
