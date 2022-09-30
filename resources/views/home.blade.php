@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" style="height: 540px">
                <div class="card-header bg-dark text-light h5">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <nav class="row">
                                <div class="nav nav-tabs mb-3 col-sm-12" id="nav-tab" role="tablist">
                                    <button class="nav-link active h5 text-primary" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Trade</button>
                                    <button class="nav-link h5 text-success" id="nav-created-tab" data-bs-toggle="tab" data-bs-target="#nav-created" type="button" role="tab" aria-controls="nav-created" aria-selected="false">Running</button>
                                    <button class="nav-link h5 text-danger" id="nav-filled-tab" data-bs-toggle="tab" data-bs-target="#nav-filled" type="button" role="tab" aria-controls="nav-filled" aria-selected="false">Closed</button>
                                </div>
                            </nav>
                            <div class="row tab-content p-3 border bg-light" id="nav-tabContent" style="height: 420px">
                                <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <h5 class="text-primary">New Trade<hr/></h5>
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
                                        <div class="mb-3 row">  
                                            <div class="col-sm-6">
                                                <label for="leverage" class="form-label">Leverage</label>
                                                <input type="number" min="10" class="form-control" placeholder="Enter leverage value"  name="leverage" id="leverage">                            
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="repurchase" class="form-label">Repurchase Precentage</label>
                                                <input type="number" step="any" name="repurchase" placeholder="Enter repurchase value" class="form-control" id="repurchase">
                                            </div>
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
                                                <option selected>Select Long/Short</option>
                                                <option value="Buy">Long</option>
                                                <option value="Sell">Short</option>                              
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Trade</button>
                                    </form> 
                                </div>
                                <div class="tab-pane fade" id="nav-created" role="tabpanel" aria-labelledby="nav-created-tab">
                                    <livewire:running-order-history />
                                </div>
                                <div class="tab-pane fade" id="nav-filled" role="tabpanel" aria-labelledby="nav-filled-tab">
                                    <livewire:closed-order-history />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
