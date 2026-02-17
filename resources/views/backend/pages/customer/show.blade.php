@extends('backend.layout.template')

@section('body-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Customer Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active">{{ $customer->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Customer Profile Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <img src="{{ $customer->avatar ?? asset('main_view/assets/img/user.png') }}" 
                         class="rounded-circle mb-3 object-fit-cover" width="100" height="100" alt="Avatar">
                    <h5 class="mb-1">{{ $customer->name }}</h5>
                    <p class="text-muted mb-1">{{ $customer->email }}</p>
                    <p class="text-muted small">Member Since: {{ $customer->created_at ? $customer->created_at->format('d M Y') : 'N/A' }}</p>
                    
                    <hr>
                    <div class="text-start">
                        <h6>Contact Info</h6>
                        <p class="mb-1"><strong class="text-muted">Address:</strong> <br> {{ $customer->address ?? 'Not provided' }}</p>
                        <p class="mb-1"><strong class="text-muted">Shipping Address:</strong> <br> {{ $customer->shipping_address ?? 'Same as address' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="col-xl-8 col-md-6 mb-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase mb-2">Total Orders</h6>
                            <h2 class="mb-0">{{ $customer->orders->count() }}</h2>
                        </div>
                    </div>
                </div>
                <!-- Add more stats here if needed -->
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="customerTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="wishlist-tab" data-bs-toggle="tab" href="#wishlist" role="tab">Wishlist</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="returns-tab" data-bs-toggle="tab" href="#returns" role="tab">Returns</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab">Reviews</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="customerTabsContent">
                        <!-- Orders Tab -->
                        <div class="tab-pane fade show active" id="orders" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customer->orders as $order)
                                        <tr>
                                            <td>#{{ $order->order_number }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>৳{{ number_format($order->total, 2) }}</td>
                                            <td><span class="badge bg-secondary">{{ $order->status }}</span></td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-xs btn-primary">View</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="text-center">No orders found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Wishlist Tab -->
                        <div class="tab-pane fade" id="wishlist" role="tabpanel">
                            <div class="row">
                                @forelse($customer->wishlists as $item)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 shadow-sm">
                                        <img src="{{ asset('uploads/' . $item->product->pro_img1) }}" class="card-img-top" alt="Product">
                                        <div class="card-body p-2">
                                            <p class="small mb-1">{{ $item->product->pro_title }}</p>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center text-muted">No items in wishlist.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Returns Tab -->
                        <div class="tab-pane fade" id="returns" role="tabpanel">
                             <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Return ID</th>
                                        <th>Product</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customer->returns as $return)
                                    <tr>
                                        <td>#{{ $return->id }}</td>
                                        <td>{{ $return->product->pro_title ?? 'Unknown' }}</td> <!-- Assuming product relation exists on return -->
                                        <td>{{ Str::limit($return->reason, 20) }}</td>
                                        <td><span class="badge bg-warning">{{ $return->status }}</span></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center">No returns found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="list-group">
                                @forelse($customer->reviews as $review)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $review->product->pro_title }}</h6>
                                        <small>{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $review->comment }}</p>
                                    <small class="text-warning">
                                        @for($i=0; $i<$review->rating; $i++) <i class="fa fa-star"></i> @endfor
                                    </small>
                                </div>
                                @empty
                                <div class="text-center text-muted p-3">No reviews found.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
