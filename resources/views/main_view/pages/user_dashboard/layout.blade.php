<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My Account') - {{ $portfolio->company_name ?? 'Pinkush' }}</title>
    <link rel="shortcut icon" href="{{ isset($portfolio->favicon) ? asset($portfolio->favicon) : asset('main_view/assets/img/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @include('main_view.include.css')
    <style>
        .dashboard-wrap { display: flex; min-height: 80vh; }
        .dashboard-sidebar { width: 260px; flex-shrink: 0; background: #f8f9fa; border-right: 1px solid #eee; padding: 1.5rem 0; }
        .dashboard-sidebar a { display: block; padding: 12px 20px; color: #333; text-decoration: none; transition: all 0.3s ease; border-left: 3px solid transparent; }
        .dashboard-sidebar a:hover { background: #e9ecef; color: #000; }
        .dashboard-sidebar a.active { background: #fff; color: var(--primary-color); border-left-color: var(--primary-color); font-weight: 600; }
        .dashboard-content { flex: 1; padding: 1.5rem 2rem; }
        @media (max-width: 768px) {
            .dashboard-wrap { flex-direction: column; }
            .dashboard-sidebar { width: 100%; border-right: none; border-bottom: 1px solid #eee; display: flex; flex-wrap: wrap; gap: 4px; }
        }
    </style>
</head>
<body>
<div class="body-wrapper">
    @include('main_view.include.header')

    @if(session('success'))
        <div class="container mt-2"><div class="alert alert-success py-2 small">{{ session('success') }}</div></div>
    @endif
    @if(session('error'))
        <div class="container mt-2"><div class="alert alert-danger py-2 small">{{ session('error') }}</div></div>
    @endif

    <div class="container py-4">
        <div class="dashboard-wrap">
            <nav class="dashboard-sidebar">
                <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}"><i class="fas fa-home me-2"></i>Dashboard</a>
                <a href="{{ route('user.orders') }}" class="{{ request()->routeIs('user.orders') ? 'active' : '' }}"><i class="fas fa-shopping-bag me-2"></i>My Orders</a>
                <a href="{{ route('user.profile.edit') }}" class="{{ request()->routeIs('user.profile.*') ? 'active' : '' }}"><i class="fas fa-user-edit me-2"></i>Edit Profile</a>
                <a href="{{ route('user.returns') }}" class="{{ request()->routeIs('user.returns') ? 'active' : '' }}"><i class="fas fa-undo me-2"></i>My Returns</a>
                <a href="{{ route('user.wishlist') }}" class="{{ request()->routeIs('user.wishlist') ? 'active' : '' }}"><i class="fas fa-heart me-2"></i>Wishlist</a>
                <a href="{{ route('user.reviews') }}" class="{{ request()->routeIs('user.reviews') ? 'active' : '' }}"><i class="fas fa-star me-2"></i>My Reviews</a>
            </nav>
            <main class="dashboard-content">
                @yield('content')
            </main>
        </div>
    </div>
    </div>
</div>

<!-- drawer cart start -->
@include('main_view.include.drawercart')
<!-- drawer cart end -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@include('main_view.include.script')
@stack('scripts')
</body>
</html>
