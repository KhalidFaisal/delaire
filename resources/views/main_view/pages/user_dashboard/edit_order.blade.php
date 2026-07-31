@extends('main_view.pages.user_dashboard.layout')

@section('title', 'Edit Order #' . $order->order_number)

@section('content')
<div class="container mb-5">
    <h2>Edit Order #{{ $order->order_number }}</h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card p-4">
                <h4>Shipping Details</h4>
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('user.order.update', $order->id) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="shipping_name" class="form-control" value="{{ old('shipping_name', $order->shipping_name) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="shipping_email" class="form-control" value="{{ old('shipping_email', $order->shipping_email) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="shipping_phone" class="form-control" value="{{ old('shipping_phone', $order->shipping_phone) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="shipping_address" class="form-control" required>{{ old('shipping_address', $order->shipping_address) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>City</label>
                            <input type="text" name="shipping_city" class="form-control" value="{{ old('shipping_city', $order->shipping_city) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Zip Code</label>
                            <input type="text" name="shipping_zip" class="form-control" value="{{ old('shipping_zip', $order->shipping_zip) }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Update Order Details</button>
                    <a href="{{ route('user.orders') }}" class="btn btn-secondary mt-4">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
