@extends('backend.layout.template')
@section('title')
    Manage Promo Codes
@endsection
@section('body-content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container card">
    <div class="content-container p-4">
        
        <!-- Create Promo Code Form -->
        <h3 class="text-center">Create New Promo Code</h3><br>
        <div class="mb-4">
            <form action="{{ route('promo.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label class="form-label">Code</label>
                        <input class="form-control" type="text" name="code" placeholder="e.g. SAVE50" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="form-label">Discount Amount</label>
                        <input class="form-control" type="number" step="0.01" name="discount_amount" placeholder="0.00" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Type</label>
                        <select class="form-select form-control" name="discount_type">
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label">Dates (Start - End)</label>
                        <div class="input-group">
                            <input class="form-control" type="date" name="start_date">
                            <span class="input-group-text">to</span>
                            <input class="form-control" type="date" name="end_date">
                        </div>
                    </div>
                </div>
                <br>
                <div class="text-end">
                    <button class="btn btn-success" type="submit"><i class="fa fa-plus"></i> Create Code</button>
                </div>
            </form>
        </div>

        <hr>

        <!-- Promo Codes List -->
        <h3 class="text-center mt-4">Active Promo Codes</h3><br>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promoCodes as $promo)
                    <tr>
                        <td><strong>{{ $promo->code }}</strong></td>
                        <td>
                            {{ $promo->discount_amount }} 
                            {{ $promo->discount_type == 'percentage' ? '%' : 'Tk' }}
                        </td>
                        <td>
                            <small>
                                {{ $promo->start_date ? $promo->start_date->format('d M Y') : 'N/A' }} - 
                                {{ $promo->end_date ? $promo->end_date->format('d M Y') : 'N/A' }}
                            </small>
                        </td>
                        <td>
                            @if($promo->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('promo.status', $promo->id) }}" class="btn btn-xs {{ $promo->status ? 'btn-warning' : 'btn-success' }}">
                                {{ $promo->status ? 'Deactivate' : 'Activate' }}
                            </a>
                            <a href="{{ route('promo.delete', $promo->id) }}" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No promo codes created yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div><br>
@endsection
