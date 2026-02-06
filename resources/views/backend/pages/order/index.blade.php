@extends('backend.layout.template')

@section('body-content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Manage Orders</h5>
                <form action="{{ route('admin.orders.index') }}" method="GET" class="row mt-3 g-2">
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing</option>
                            <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="Delivered" {{ request('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="date_filter" class="form-select" onchange="this.form.submit()">
                            <option value="">All Dates</option>
                            <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today's Orders</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="basic-1">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr class="{{ !$order->is_viewed ? 'fw-bold table-warning' : '' }}">
                                <td>
                                    {{ $order->order_number }}
                                    @if(!$order->is_viewed)
                                        <span class="badge bg-danger rounded-pill ms-1">New</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $firstItem = $order->items->first();
                                    @endphp
                                    @if($firstItem && $firstItem->product)
                                        <div class="d-flex align-items-center">
                                            @if($firstItem->product->pro_img1)
                                                <img src="{{ asset('uploads/' . $firstItem->product->pro_img1) }}" 
                                                     alt="Product" 
                                                     class="img-fluid rounded me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <span class="d-block text-truncate" style="max-width: 150px;" title="{{ $firstItem->product->pro_title }}">
                                                    {{ $firstItem->product->pro_title }}
                                                </span>
                                                @if($order->items->count() > 1)
                                                    <small class="text-muted">+{{ $order->items->count() - 1 }} more</small>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">No Items</span>
                                    @endif
                                </td>
                                <td>{{ $order->user ? $order->user->name : 'Guest' }}</td>
                                <td>৳{{ number_format($order->total, 2) }}</td>
                                <td>
                                    @if($order->status == 'Pending')
                                        <span class="badge bg-warning text-dark">{{ $order->status }}</span>
                                    @elseif($order->status == 'Processing')
                                        <span class="badge bg-primary">{{ $order->status }}</span>
                                    @elseif($order->status == 'Shipped')
                                        <span class="badge bg-info">{{ $order->status }}</span>
                                    @elseif($order->status == 'Delivered')
                                        <span class="badge bg-success">{{ $order->status }}</span>
                                    @elseif($order->status == 'Cancelled')
                                        <span class="badge bg-danger">{{ $order->status }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
