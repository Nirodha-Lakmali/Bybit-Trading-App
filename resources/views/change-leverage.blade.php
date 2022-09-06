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
                            <h5 class="h5 col-sm-8">Change Leverage</h5>
                            <div class="btn-group col-sm-4">
                                <a class="btn btn-dark" href="{{ route('open-order') }}">Open Order</a>
                                <a class="btn btn-success" href="/home">Back</a>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <form action="{{ route('change-leverage') }}" class="form" method="post">
                                @csrf
                                <div class="mb-3">  
                                    <label for="leverage" class="col-sm-6 form-label">Leverage</label>
                                    <input type="number" min="10" class="col-sm-6 form-control" name="leverage" id="leverage">                            
                                </div>
                                <div class="mb-3">      
                                    <button type="submit" class="btn btn-primary">Change</button>
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
