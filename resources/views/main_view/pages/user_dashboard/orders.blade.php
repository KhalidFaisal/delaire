@extends('main_view.pages.user_dashboard.layout')

@section('title', 'My Orders')

@section('content')
<h4 class="mb-4">My Orders</h4>
@if($orders->isEmpty())
    <p class="text-muted">You have no orders yet.</p>
@else
    <div class="orders-container">
        @foreach($orders as $order)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <div>
                    <span class="fw-bold text-dark">Order #{{ $order->order_number }}</span>
                    <small class="text-muted ms-2">{{ $order->created_at->format('M d, Y') }}</small>
                </div>
                <div>
                    <span class="badge rounded-pill {{ $order->status == 'pending' ? 'bg-warning text-dark' : ($order->status == 'delivered' ? 'bg-success' : 'bg-secondary') }} px-3 py-2">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        @foreach($order->items as $item)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('uploads/'.$item->product->pro_img1) }}" alt="{{ $item->product->pro_title }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 text-dark" style="font-size: 15px;">{{ $item->product->pro_title }}</h6>
                                <p class="mb-0 text-muted small">
                                    Size: {{ $item->size ?? 'N/A' }} | 
                                    Qty: {{ $item->qty }} | 
                                    Price: ৳{{ number_format($item->price, 2) }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="col-md-4 border-start-md d-flex flex-column justify-content-center align-items-md-end mt-3 mt-md-0">
                        <div class="mb-3 text-md-end">
                            <small class="text-muted d-block">Total Amount</small>
                            <span class="fs-5 fw-bold text-dark">৳{{ number_format($order->total, 2) }}</span>
                        </div>
                        
                        <div class="d-flex gap-2 flex-wrap justify-content-md-end">
                            @if(in_array($order->status, ['Processing', 'Shipped', 'Delivered']))
                                <a href="{{ route('user.orders.invoice', $order->id) }}" class="btn btn-sm btn-outline-info">Invoice</a>
                            @endif

                            @if($order->status == 'pending' || $order->status == 'Pending')
                                <a href="{{ route('user.order.edit', $order->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('user.order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                </form>
                            @elseif($order->status == 'delivered')
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#returnModal{{ $order->id }}">
                                    Return
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($order->status == 'delivered')
        <!-- Return Modal -->
        <div class="modal fade" id="returnModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('user.return.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title">Return Order #{{ $order->order_number }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Reason for Return</label>
                                <select name="reason" class="form-select" required>
                                    <option value="" disabled selected>Select a reason</option>
                                    <option value="Damaged">Damaged Product</option>
                                    <option value="Wrong Item">Wrong Item</option>
                                    <option value="Size Issue">Size Issue</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Please describe the issue..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary px-4">Submit Return</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
@endif

<style>
    .border-start-md {
        border-left: 1px solid #eee;
    }
    @media (max-width: 768px) {
        .border-start-md {
            border-left: none;
            border-top: 1px solid #eee;
            padding-top: 1rem;
        }
    }
</style>
@endsection
