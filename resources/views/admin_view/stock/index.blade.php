@extends('backend.layout.template')

@section('title')
Current Stock Items
@endsection

@section('body-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Current Stock Items</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Inventory</li>
                    <li class="breadcrumb-item active">Current Stock</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom p-4">
                    <h5 class="mb-0 text-uppercase text-muted" style="font-size: 0.9rem; letter-spacing: 1px;">Stock Overview</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle display" id="basic-1" style="width:100%">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Product</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Size</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lot No.</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Starting</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sold</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Current</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            @if($item['product_image'])
                                                <img src="{{ asset('uploads/'.$item['product_image']) }}" class="lazy-image avatar avatar-sm me-3 border-radius-lg shadow-sm" alt="product" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;" loading="lazy" >
                                            @else
                                                <div class="avatar avatar-sm me-3 border-radius-lg bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 8px;">
                                                    <i class="fa fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm font-weight-bold" style="color: #344767;">{{ $item['product_name'] }}</h6>
                                                <p class="text-xs text-muted mb-0">{{ $item['entry_date'] ? \Carbon\Carbon::parse($item['entry_date'])->format('d M Y') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-light text-dark border">{{ $item['size'] }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $item['lot_number'] }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $item['starting_qty'] }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-danger text-xs font-weight-bold">-{{ $item['sold_qty'] }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <h6 class="mb-0 text-sm {{ $item['current_stock'] <= 5 ? 'text-danger' : 'text-success' }}">{{ $item['current_stock'] }}</h6>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if($item['current_stock'] == 0)
                                            <span class="badge badge-sm bg-gradient-secondary">Out of Stock</span>
                                        @elseif($item['current_stock'] <= 5)
                                            <span class="badge badge-sm bg-gradient-warning">Low Stock</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-success">In Stock</span>
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
</div>

<style>
    .card {
        box-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
        border-radius: 1rem;
    }
    .table thead th {
        padding: 0.75rem 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e9ecef;
    }
    .table tbody td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }
    .avatar {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
    }
    .text-xxs {
        font-size: 0.75rem !important;
    }
    .badge-light {
        background-color: #f8f9fa;
        color: #6c757d;
    }
    .bg-gradient-success {
        background-image: linear-gradient(310deg, #17ad37 0%, #98ec2d 100%);
        color: white;
    }
    .bg-gradient-warning {
        background-image: linear-gradient(310deg, #fb6340 0%, #fbb140 100%);
        color: white;
    }
    .bg-gradient-secondary {
        background-image: linear-gradient(310deg, #627594 0%, #a8b8d8 100%);
        color: white;
    }
</style>
@endsection
