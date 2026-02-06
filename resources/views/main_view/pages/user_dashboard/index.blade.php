@extends('main_view.pages.user_dashboard.layout')

@section('title', 'Dashboard')

@section('content')
<h4 class="mb-4">My Account</h4>
<div class="row g-3">
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('user.orders') }}" class="card text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="fas fa-shopping-bag fa-2x text-primary mb-2"></i>
                <h5 class="card-title">My Orders</h5>
                <p class="mb-0">{{ $ordersCount }}</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('user.profile.edit') }}" class="card text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="fas fa-user-edit fa-2x text-primary mb-2"></i>
                <h5 class="card-title">Edit Profile</h5>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('user.returns') }}" class="card text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="fas fa-undo fa-2x text-primary mb-2"></i>
                <h5 class="card-title">My Returns</h5>
                <p class="mb-0">{{ $returnsCount }}</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('user.wishlist') }}" class="card text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="fas fa-heart fa-2x text-primary mb-2"></i>
                <h5 class="card-title">Wishlist</h5>
                <p class="mb-0">{{ $wishlistCount }}</p>
            </div>
        </a>
    </div>
</div>
@endsection
