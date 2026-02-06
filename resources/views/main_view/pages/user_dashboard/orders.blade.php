@extends('main_view.pages.user_dashboard.layout')

@section('title', 'My Orders')

@section('content')
<h4 class="mb-4">My Orders</h4>
@if($orders->isEmpty())
    <p class="text-muted">You have no orders yet.</p>
@else
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>
                        @foreach($order->items as $item)
                        <div class="d-flex align-items-center mb-2 border-bottom pb-1">
                            <img src="{{ asset('uploads/'.$item->product->pro_img1) }}" alt="" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                            <div>
                                <h6 class="mb-0" style="font-size: 14px;">{{ $item->product->pro_title }}</h6>
                                <small class="text-muted">
                                    Size: {{ $item->size ?? 'N/A' }} | 
                                    Qty: {{ $item->qty }} | 
                                    Price: ৳{{ number_format($item->price, 2) }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </td>
                    <td>৳{{ number_format($order->total, 2) }}</td>
                    <td>
                        <span class="badge {{ $order->status == 'pending' ? 'bg-warning' : ($order->status == 'delivered' ? 'bg-success' : 'bg-secondary') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td>
                        @if(in_array($order->status, ['Processing', 'Shipped', 'Delivered']))
                            <a href="{{ route('user.orders.invoice', $order->id) }}" class="btn btn-sm btn-info mb-1">Invoice</a>
                        @endif

                        @if($order->status == 'pending' || $order->status == 'Pending')
                            <a href="{{ route('user.order.edit', $order->id) }}" class="btn btn-sm btn-primary mb-1">Edit</a>
                            <form action="{{ route('user.order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                            </form>
                        @elseif($order->status == 'delivered')
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#returnModal{{ $order->id }}">
                                Return
                            </button>
                            
                            <!-- Return Modal -->
                            <div class="modal fade" id="returnModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('user.return.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Return Order #{{ $order->order_number }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Reason</label>
                                                    <select name="reason" class="form-control" required>
                                                        <option value="Damaged">Damaged Product</option>
                                                        <option value="Wrong Item">Wrong Item</option>
                                                        <option value="Size Issue">Size Issue</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit Return</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
@endif
@endsection
