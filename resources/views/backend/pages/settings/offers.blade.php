@extends('backend.layout.template')
@section('title')
    Manage Offers
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
        <h3 class="text-center">Manage Offers (Global Discount)</h3><br>
        <form action="{{ route('update.offers') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label" for="offer_name">Offer Name</label>
                    <input class="form-control" id="offer_name" type="text" name="offer_name" value="{{ $setting->offer_name }}" placeholder="e.g. Eid Flash Sale">
                </div>
                <div class="col-md-6 form-group">
                     <label class="form-label" for="discount_percentage">Offer Amount (Global Discount %)</label>
                    <input class="form-control" id="discount_percentage" type="number" step="0.01" name="discount_percentage" value="{{ $setting->discount_percentage }}" required>
                    <small class="text-muted">Applied to ALL products based on adjusted price.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label" for="offer_start_date">Offer Start Date</label>
                    <input class="form-control" id="offer_start_date" type="datetime-local" name="offer_start_date" value="{{ $setting->offer_start_date ? \Carbon\Carbon::parse($setting->offer_start_date)->format('Y-m-d\TH:i') : '' }}">
                </div>
                <div class="col-md-6 form-group">
                     <label class="form-label" for="offer_end_date">Offer Expiration Date</label>
                    <input class="form-control" id="offer_end_date" type="datetime-local" name="offer_end_date" value="{{ $setting->offer_end_date ? \Carbon\Carbon::parse($setting->offer_end_date)->format('Y-m-d\TH:i') : '' }}">
                </div>
            </div>

            <br>
            <button class="btn btn-primary" type="submit">Update Offer Settings</button>
        </form>
    </div>
</div><br>
@endsection
