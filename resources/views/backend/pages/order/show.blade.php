@extends('backend.layout.template')

@section('body-content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Order Details: #{{ $order->order_number }}</h5>
                <div>
                    <a href="{{ route('admin.orders.pdf', $order->id) }}" class="btn btn-warning me-2"><i class="fa fa-file-pdf-o"></i> Download Invoice</a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Orders</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-4">
                        <h6>Customer Info</h6>
                        <p>
                            <strong>Name:</strong> {{ $order->user ? $order->user->name : 'Guest' }}<br>
                            <strong>Email:</strong> {{ $order->user ? $order->user->email : 'N/A' }}
                            @if($order->admin_id)
                                <br><strong class="text-info">Created by Admin</strong>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6>Shipping Info</h6>
                        <p>
                            <strong>Name:</strong> {{ $order->shipping_name }}<br>
                            <strong>Phone:</strong> {{ $order->shipping_phone }}<br>
                            <strong>Address:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }} - {{ $order->shipping_zip }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6>Order Status</h6>
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <select name="status" class="form-select">
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->pro_img1)
                                            <img src="{{ asset('uploads/' . $item->product->pro_img1) }}" 
                                                 alt="{{ $item->product->pro_title }}" 
                                                 style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;" loading="lazy"  class="lazy-image" >
                                        @endif
                                        <span>{{ $item->product ? $item->product->pro_title : 'Product Removed' }}</span>
                                    </div>
                                </td>
                                <td>৳{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>৳{{ number_format($item->price * $item->qty, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td>{{ number_format($order->subtotal > 0 ? $order->subtotal : ($order->total - $order->delivery_charge + $order->promo_discount), 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Delivery Charge:</strong></td>
                                <td>{{ number_format($order->delivery_charge, 2) }}</td>
                            </tr>
                            @if($order->promo_discount > 0)
                            <tr>
                                <td colspan="3" class="text-end text-success"><strong>Discount ({{ $order->promo_code }}):</strong></td>
                                <td class="text-success">-{{ number_format($order->promo_discount, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                                <td><strong>৳{{ number_format($order->total, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
