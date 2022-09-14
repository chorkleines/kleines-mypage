@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">個別会計残高</h5>
                        <p class="card-text">
                            {{ Auth::user()->getBalance() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">個別会計残高</h5>
                        <p class="card-text">
                            {{ Auth::user()->getArrears() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
