@extends('backend.layout.template')
@section('title')
    Inventory Management
@endsection
@section('body-content')
<div class="container card">
    <div class="content-container p-4">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <h3 class="text-center">Inventory Management</h3><br>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h5>Total Lifetime Stock</h5>
                        <h3>{{ $totalLifetimeStock }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h5>Current Stock Summary</h5>
                        <h3>{{ $currentStock }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12 text-end">
                <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Stock</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-light">
                        <th width="20%">Date / Time</th>
                        <th width="15%">Lot Number</th>
                        <th width="10%">Type</th>
                        <th width="55%">Stock Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($batches as $batch)
                    <tr>
                        <td class="align-middle">
                            <strong>{{ \Carbon\Carbon::parse($batch->entry_date)->format('Y-m-d') }}</strong><br>
                            <small class="text-muted">{{ $batch->created_at->format('h:i A') }}</small>
                        </td>
                        <td class="align-middle">{{ $batch->lot_number ?? 'N/A' }}</td>
                        <td class="align-middle">
                            @if($batch->type == 'initial') <span class="badge bg-info">Initial</span>
                            @elseif($batch->type == 'purchase') <span class="badge bg-primary">Purchase</span>
                            @elseif($batch->type == 'damage') <span class="badge bg-danger">Damage</span>
                            @elseif($batch->type == 'return') <span class="badge bg-warning">Return</span>
                            @else <span class="badge bg-secondary">{{ $batch->type }}</span>
                            @endif
                        </td>
                        <td class="p-0">
                            <table class="table table-sm mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $batchKey = $batch->created_at->format('Y-m-d H:i:s');
                                        $batchItems = $stocks[$batchKey] ?? [];
                                    @endphp
                                    @foreach($batchItems as $item)
                                    <tr>
                                        <td>
                                            @if($item->product)
                                                <div class="d-flex align-items-center">
                                                    @if($item->product->pro_img1)
                                                    <img src="{{ asset('uploads/'.$item->product->pro_img1) }}" style="width: 30px; height: 30px; object-fit: cover; margin-right: 5px;">
                                                    @endif
                                                    {{ $item->product->pro_title }}
                                                </div>
                                            @else
                                                <span class="text-danger">Product Deleted</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->productSize ? $item->productSize->size : 'N/A' }}</td>
                                        <td>
                                            <span class="font-weight-bold">{{ $item->quantity }}</span>
                                        </td>
                                        <td><small>{{ $item->note }}</small></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $batches->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    // Optional: Add search functionality similar to products page
</script>
@endsection
