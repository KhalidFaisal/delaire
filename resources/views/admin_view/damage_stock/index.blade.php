@extends('backend.layout.template')
@section('title')
    Damage Stock Management
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

        <h3 class="text-center">Damage Stock Management</h3><br>

        <div class="row mb-3">
            <div class="col-md-12 text-end">
                <a href="{{ route('admin.damage.create') }}" class="btn btn-danger"><i class="fa fa-minus"></i> Record New Damage</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped" id="damageTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Lot Number</th>
                        <th>Quantity (Damaged)</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($damageStocks as $stock)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($stock->entry_date)->format('Y-m-d') }}</td>
                        <td>{{ $stock->product->pro_title }}</td>
                        <td>{{ $stock->productSize ? $stock->productSize->size : 'N/A' }}</td>
                        <td>{{ $stock->lot_number }}</td>
                        <td>{{ abs($stock->quantity) }}</td>
                        <td>{{ $stock->note }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $damageStocks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
