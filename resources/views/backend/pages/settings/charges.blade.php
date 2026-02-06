@extends('backend.layout.template')
@section('title')
    Manage Charges
@endsection
@section('body-content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container card">
    <div class="content-container p-4">
        <h3 class="text-center">Manage Charges & Price Hikes</h3><br>
        <form action="{{ route('update.charges') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 form-group">
                     <label class="form-label" for="delivery_charge">Delivery Charge (Amount)</label>
                    <input class="form-control" id="delivery_charge" type="number" step="0.01" name="delivery_charge" value="{{ $setting->delivery_charge }}" required>
                    <small class="text-muted">Set to 0 for Free Delivery.</small>
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label" for="profit_percentage">Global Profit Increase (%)</label>
                    <input class="form-control" id="profit_percentage" type="number" step="0.01" name="profit_percentage" value="{{ $setting->profit_percentage }}" required>
                    <small class="text-muted">Increases base price before discount.</small>
                </div>
            </div>

             <!-- Hidden fields to preserve other settings when updating this section -->
            <input type="hidden" name="discount_percentage" value="{{ $setting->discount_percentage }}">
            <input type="hidden" name="offer_name" value="{{ $setting->offer_name }}">
            <input type="hidden" name="offer_start_date" value="{{ $setting->offer_start_date }}">
            <input type="hidden" name="offer_end_date" value="{{ $setting->offer_end_date }}">

            <br>
            <button class="btn btn-primary" type="submit">Update Charges</button>
        </form>
    </div>
</div><br>
@endsection
