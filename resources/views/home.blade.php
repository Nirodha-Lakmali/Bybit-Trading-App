@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header h4">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    <div class="row">
                        <h5 class="h5 col-sm-10">Open Trade</h5> 
                        <hr/>
                        <div class="col-sm-12">
                            <form method="post" action="{{ route('home')}}">
                                @csrf              
                                <div class="mb-3">  
                                    <label for="currency" class="form-label">Currency pair</label>
                                    <select class="form-select" id="currency" name="symbol" aria-label="Default select">
                                        <option selected>Select currency pair</option>
                                        @foreach ($currencies as $currency)
                                            <option value="{{$currency->symbol}}">{{ $currency->symbol}}</option>
                                        @endforeach                                   
                                    </select>
                                </div>
                                <div class="mb-3">  
                                    <label for="leverage" class="form-label">Leverage</label>
                                    <input type="number" min="10" class="form-control" placeholder="Enter leverage value"  name="leverage" id="leverage">                            
                                </div>
                                <div class="mb-3 row">
                                    <div class="col-sm-6">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="number" step="any" name="price" placeholder="Enter price value" class="form-control" id="price">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" step="any" max="100" name="qty" placeholder="Enter quantity value" class="form-control" id="quantity">
                                    </div>
                                </div>
                                <div class="mb-3">  
                                    <select class="form-select" id="side" name="side" aria-label="Default select">
                                        <option selected>Select sell/buy</option>
                                        <option value="Sell">Sell</option>
                                        <option value="Buy">Buy</option>                              
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-dark">Trade</button>
                            </form> 
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
