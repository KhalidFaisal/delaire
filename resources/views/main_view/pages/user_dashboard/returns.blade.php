@extends('main_view.pages.user_dashboard.layout')

@section('title', 'My Returns')

@section('content')
<h4 class="mb-4">My Returns</h4>

<div class="card mb-4">
    <div class="card-header">Request a return</div>
    <div class="card-body">
        <form action="{{ route('user.returns.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-2">
                <label class="form-label">Select Product to Return</label>
                <div class="d-flex gap-3">
                    <div class="flex-grow-1">
                        <select name="product_id" id="productSelect" class="form-select form-select-sm" required onchange="updateReturnForm()">
                            <option value="">— Select Product —</option>
                            @foreach($orders as $order)
                                <optgroup label="Order #{{ $order->order_number }} ({{ $order->created_at->format('M d, Y') }})">
                                    @foreach($order->items as $item)
                                        <option value="{{ $item->product->id }}" 
                                                data-order-id="{{ $order->id }}"
                                                data-image="{{ asset('storage/' . $item->product->pro_img) }}"
                                                data-name="{{ $item->product->pro_name }}">
                                            {{ $item->product->pro_name }} (Qty: {{ $item->qty }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <input type="hidden" name="order_id" id="orderIdInput">
                    </div>
                    <div id="productPreview" style="display:none; width: 60px; height: 60px; border: 1px solid #ddd; padding: 2px;">
                        <img src="" id="previewImg" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label">Upload Image (Optional)</label>
                <input type="file" name="return_image" class="form-control form-control-sm" accept="image/*">
            </div>

            <div class="mb-2">
                <label class="form-label">Reason</label>
                <input type="text" name="reason" class="form-control form-control-sm" placeholder="e.g. Wrong size" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control form-control-sm" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Submit return request</button>
        </form>
    </div>
</div>

@if($returns->isEmpty())
    <p class="text-muted">No return requests.</p>
@else
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($returns as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($r->product)
                                <img src="{{ asset('storage/' . $r->product->pro_img) }}" width="40" height="40" class="me-2" style="object-fit:cover; border:1px solid #eee;">
                                <div>
                                    <small class="d-block fw-bold">{{ $r->product->pro_name }}</small>
                                    <small class="text-muted">Order #{{ $r->order?->order_number }}</small>
                                </div>
                            @else
                                <span class="text-muted">Product Deleted</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        {{ $r->reason ?? '—' }}
                        @if($r->return_image)
                            <br>
                            <a href="{{ asset('storage/' . $r->return_image) }}" target="_blank" class="text-xs text-primary">View Image</a>
                        @endif
                    </td>
                    <td><span class="badge bg-secondary">{{ $r->status }}</span></td>
                    <td>{{ $r->created_at->format('M d, Y') }}</td>
                    <td>
                        <form action="{{ route('user.returns.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this return request?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $returns->links() }}
@endif

<script>
    function updateReturnForm() {
        var select = document.getElementById('productSelect');
        var option = select.options[select.selectedIndex];
        var orderId = option.getAttribute('data-order-id');
        var imgSrc = option.getAttribute('data-image');
        
        document.getElementById('orderIdInput').value = orderId || '';
        
        var previewDiv = document.getElementById('productPreview');
        var previewImg = document.getElementById('previewImg');
        
        if (imgSrc) {
            previewImg.src = imgSrc;
            previewDiv.style.display = 'block';
        } else {
            previewDiv.style.display = 'none';
        }
    }
</script>
@endsection
