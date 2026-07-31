@extends('backend.layout.template')

@section('body-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Damage Stock Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Products</li>
                    <li class="breadcrumb-item active">Damage Stock</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Summary Card -->
    <div class="col-sm-12 col-xl-12">
        <div class="card bg-primary">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <h4 class="text-white">Total Damaged Items</h4>
                        <h2 class="text-white">{{ $totalItems }}</h2>
                    </div>
                    <div class="col-6 text-end">
                        <h4 class="text-white">Total Value (approx)</h4>
                        <h2 class="text-white">৳{{ number_format($totalPrice, 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Damage Stock List -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Damaged Products List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display" id="basic-1">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Order Info</th>
                                <th>User</th>
                                <th>Reason</th>
                                <th>Received Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($damagedItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->pro_img1)
                                            <img src="{{ asset('uploads/' . $item->product->pro_img1) }}" alt="" width="50" class="lazy-image me-2 rounded" loading="lazy" >
                                        @else
                                            <img src="{{ asset('backend/images/product/1.png') }}" alt="" width="50" class="lazy-image me-2 rounded" loading="lazy" >
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->pro_title ?? 'Product Deleted' }}</h6>
                                            <span>Price: ৳{{ $item->product->pro_sprice ?? '0.00' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $item->order_id) }}" class="text-decoration-underline text-primary">
                                        #{{ $item->order->order_number ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $item->user->name ?? 'Unknown' }}</h6>
                                            <span>{{ $item->order->shipping_phone ?? '' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-danger">{{ $item->reason }}</span>
                                    @if($item->description)
                                        <p class="text-muted small mb-0 mt-1" style="max-width: 200px;">{{ Str::limit($item->description, 50) }}</p>
                                    @endif
                                    @if($item->return_image)
                                        <a href="{{ asset('storage/' . $item->return_image) }}" target="_blank" class="text-primary small d-block mt-1">
                                            <i class="fa fa-image"></i> View Image
                                        </a>
                                    @endif
                                </td>
                                <td>{{ $item->updated_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- Restore to Stock -->
                                        <form action="{{ route('admin.damage.restore', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to move this item back to current stock?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Move to Current Stock">
                                                <i class="fa fa-refresh"></i> Restock
                                            </button>
                                        </form>

                                        <!-- Delete (Dispose) -->
                                        <form action="{{ route('admin.damage.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this record? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete Record">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <i class="fa fa-folder-open-o fs-2 mb-2 text-muted"></i>
                                        <h6 class="mb-0">No data found</h6>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
