@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">

                    <div class="row">
                        <hr>
                        <div class="row">
                            <h5 class="h5 col-sm-10">Trade Open</h5>
                            <a class="btn btn-dark col-sm-2" href="{{ route('change-leverage') }}">Back</a>
                        </div>           
                        <div class="col-sm-12">
                            <form action="{{ route('open-order') }}" class="form" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" step="any" name="price" class="form-control" id="price">               
                                </div>
                                <div class="mb-3 row">                                         
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" step="any" name="quantity" class="form-control" id="quantity">                                  
                                </div>
                                <div class="mb-3">      
                                    <button type="submit" class="btn btn-primary">Open Trade</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
