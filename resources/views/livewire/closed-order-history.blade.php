<div>
    <h5 class="text-danger">Closed Trade List<hr/></h5>
    <div>
        <table class="table">
            <thead>
                <tr class="row m-1 text-center">
                  <th class="col-sm-2">Id</th>
                  <th class="col-sm-2">Symbol</th>
                  <th class="col-sm-2">Qty</th>
                  <th class="col-sm-2">Order Price</th>
                  <th class="col-sm-2">Trade Type</th>
                  <th class="col-sm-2">Order Type</th>
                </tr>
              </thead>
              <tbody style="height:280px;overflow-y:scroll;overflow-x:hidden;">
                @foreach($orders as $order)
                    @if($order->order_status == 'Closed')    
                        <tr class="row m-1 text-center">
                            <td class="col-sm-2">{{ $order->id }}</td>
                            <td class="col-sm-2">{{ $order->symbol }}</td>
                            <td class="col-sm-2">{{ $order->qty }}</td>
                            <td class="col-sm-2">{{ $order->price }}</td>                    
                            <td class="col-sm-2">
                                @if ($order->order_status == 'Closed')
                                    Open {{ $order->side }}
                                @endif
                            </td>                 
                            <td class="col-sm-2">{{ $order->order_type }}</td>
                        </tr>
                    @endif
                @endforeach
              </tbody>
        </table>
    </div>
</div>
