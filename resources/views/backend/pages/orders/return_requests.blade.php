@extends('backend.layout.template')
@section('body-content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Return Requests</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display" id="basic-1">
                        <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Product</th>
                                <th>User Info</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returns as $return)
                            <tr class="{{ $return->status == 'requested' ? 'fw-bold table-warning' : '' }}">
                                <td>
                                    <a href="{{ route('admin.orders.show', $return->order_id) }}" class="text-decoration-underline text-primary">
                                        #{{ $return->order->order_number ?? 'N/A' }}
                                    </a>
                                    @if($return->status == 'requested')
                                        <span class="badge bg-danger rounded-pill ms-1">New</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($return->product->pro_img1)
                                            <img src="{{ asset('uploads/' . $return->product->pro_img1) }}" alt="" width="50" class="me-2 rounded">
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $return->product->pro_title ?? 'Product not found' }}</h6>
                                            <span>৳{{ $return->order->subtotal ?? '0.00' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($return->user->avatar)
                                            <img src="{{ asset('backend/images/profile/' . $return->user->avatar) }}" alt="" width="40" class="rounded-circle me-2">
                                        @else
                                             <img src="{{ asset('backend/images/profile/profile.png') }}" alt="" width="40" class="rounded-circle me-2">
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $return->user->name ?? 'Unknown User' }}</h6>
                                            <span>{{ $return->order->shipping_phone ?? 'Phone N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $return->reason }}</strong>
                                    <p class="text-muted small mb-1">{{ $return->description }}</p>
                                    @if($return->return_image)
                                        <a href="{{ asset('storage/' . $return->return_image) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                            <i class="fa fa-image"></i> View Image
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if($return->status == 'requested')
                                        <span class="badge badge-warning">Requested</span>
                                    @elseif($return->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($return->status == 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                     @elseif($return->status == 'completed')
                                        <span class="badge badge-info">Completed</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($return->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($return->status == 'requested')
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('admin.returns.status', $return->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-success" title="Accept (Restock)"><i class="fa fa-check"></i></button>
                                            </form>
                                            <form action="{{ route('admin.returns.status', $return->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="approved_damaged">
                                                <button type="submit" class="btn btn-sm btn-warning" title="Accept (Damaged)" onclick="return confirm('Are you sure this item is damaged? It will be moved to Damage Stock.')"><i class="fa fa-exclamation-triangle"></i></button>
                                            </form>
                                            <form action="{{ route('admin.returns.status', $return->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Deny"><i class="fa fa-times"></i></button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted">No actions</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
