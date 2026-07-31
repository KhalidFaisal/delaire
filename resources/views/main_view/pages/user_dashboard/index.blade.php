@extends('main_view.pages.user_dashboard.layout')

@section('title', 'Dashboard')

@section('content')
<h4 class="mb-4 fw-bold">My Account</h4>
<div class="row g-4">
    <style>
        .dashboard-card {
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            padding: 30px 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-color: transparent;
        }
        .dashboard-card i {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 15px;
            transition: color 0.3s ease;
        }
        .dashboard-card:hover i {
            color: var(--primary-color);
        }
        .dashboard-card h5 {
            color: #333;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
            transition: color 0.3s ease;
        }
        .dashboard-card:hover h5 {
            color: var(--primary-color);
        }
        .dashboard-card p {
            color: #888;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>

    <div class="col-md-6 col-lg-3">
        <a href="{{ route('user.orders') }}" class="text-decoration-none h-100 d-block">
            <div class="dashboard-card">
                <i class="fas fa-shopping-bag"></i>
                <h5 class="card-title">My Orders</h5>
                <p class="mb-0">{{ $ordersCount }} Orders</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('user.profile.edit') }}" class="text-decoration-none h-100 d-block">
            <div class="dashboard-card">
                <i class="fas fa-user-edit"></i>
                <h5 class="card-title">Edit Profile</h5>
                <p class="mb-0">Manage Details</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('user.returns') }}" class="text-decoration-none h-100 d-block">
            <div class="dashboard-card">
                <i class="fas fa-undo"></i>
                <h5 class="card-title">My Returns</h5>
                <p class="mb-0">{{ $returnsCount }} Returns</p>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('user.wishlist') }}" class="text-decoration-none h-100 d-block">
            <div class="dashboard-card">
                <i class="fas fa-heart"></i>
                <h5 class="card-title">Wishlist</h5>
                <p class="mb-0">{{ $wishlistCount }} Items</p>
            </div>
        </a>
    </div>
</div>
@endsection
