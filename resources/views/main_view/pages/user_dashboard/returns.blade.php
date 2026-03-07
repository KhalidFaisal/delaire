@extends('main_view.pages.user_dashboard.layout')

@section('title', 'My Returns')

@section('content')
<h4 class="mb-4 fw-bold">My Returns</h4>

<!-- Eligible Items Section -->
@if(isset($orders) && $orders->count() > 0)
<div class="mb-5">
    <h5 class="mb-3 text-muted">Items Eligible for Return</h5>
    <div class="row g-3">
        @foreach($orders as $order)
            @foreach($order->items as $item)
                @php 
                    // Check if already returned/requested to avoid duplicates if logic allows
                    // Ideally handled in controller, but for UI clarity:
                    // We simply list all delivered items. If strict logic needed, filter in controller.
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm p-3">
                        <div class="d-flex mb-3">
                            <img src="{{ asset('uploads/' . $item->product->pro_img1) }}" alt="{{ $item->product->pro_title }}" class="lazy-image rounded" style="width: 70px; height: 70px; object-fit: cover;" loading="lazy" >
                            <div class="ms-3">
                                <h6 class="mb-1 fw-bold text-dark">{{ Str::limit($item->product->pro_title ?? 'Unknown Product', 40) }}</h6>
                                <p class="text-muted small mb-0">Order #{{ $order->order_number }}</p>
                                <p class="text-muted small mb-0">Delivered: {{ $order->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-danger w-100 btn-sm mt-auto" data-bs-toggle="modal" data-bs-target="#returnModal{{ $order->id }}-{{ $item->product->id }}">
                            Request Return
                        </button>
                    </div>
                </div>

                <!-- Return Modal -->
                <div class="modal fade" id="returnModal{{ $order->id }}-{{ $item->product->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('user.returns.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Return Request</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                                        <img src="{{ asset('uploads/' . $item->product->pro_img1) }}" class="lazy-image rounded" style="width: 50px; height: 50px; object-fit: cover;" loading="lazy" >
                                        <div class="ms-3">
                                            <h6 class="mb-0 fw-bold">{{ $item->product->pro_title }}</h6>
                                            <small class="text-muted">Qty: {{ $item->qty }}</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Reason for Return</label>
                                        <select name="reason" class="form-select" required>
                                            <option value="">Select a reason</option>
                                            <option value="Damaged/Defective">Damaged or Defective</option>
                                            <option value="Wrong Item">Received Wrong Item</option>
                                            <option value="Size/Fit Issue">Size/Fit Issue</option>
                                            <option value="Quality Not As Expected">Quality Not As Expected</option>
                                            <option value="Changed Mind">Changed Mind</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Upload Image (Optional)</label>
                                        <input type="file" name="return_image" class="form-control" accept="image/*">
                                        <div class="form-text">Helpful for damaged items.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">Additional Comments</label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="Please provide more details..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Submit Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>
<hr class="my-5 opacity-25">
@endif

<h5 class="mb-3 text-muted">Return History</h5>
@if($returns->isEmpty())
    <div class="text-center py-5 bg-light rounded text-muted">
        <i class="fas fa-undo fa-2x mb-3 opacity-25"></i>
        <p>No return requests found.</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="bg-light">
                <tr>
                    <th>Product</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returns as $r)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($r->product)
                                <img src="{{ asset('uploads/' . $r->product->pro_img1) }}" width="40" height="40" class="lazy-image me-2 rounded" style="object-fit:cover;" loading="lazy" >
                                <div>
                                    <span class="d-block fw-bold small">{{ $r->product->pro_title }}</span>
                                    <small class="text-muted">Order #{{ $r->order?->order_number }}</small>
                                </div>
                            @else
                                <span class="text-muted">Product Unavailable</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="d-block small">{{ $r->reason ?? '—' }}</span>
                        @if($r->return_image)
                            <a href="{{ asset('storage/' . $r->return_image) }}" target="_blank" class="text-primary small text-decoration-none"><i class="fas fa-image me-1"></i>View Image</a>
                        @endif
                    </td>
                    <td>
                        @if($r->status == 'requested')
                            <span class="badge bg-warning text-dark">Requested</span>
                        @elseif($r->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($r->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($r->status) }}</span>
                        @endif
                    </td>
                    <td class="small">{{ $r->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($r->status == 'requested')
                        <form action="{{ route('user.returns.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this return request?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Cancel</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $returns->links() }}
@endif
@endsection
