@extends('backend.layout.template')
@section('title')
    Manage Feature Categories
@endsection
@section('body-content')
<div class="container card">
    <div class="content-container p-4">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h3 class="text-center mb-4">Manage Feature Categories</h3>
        
        <form action="{{ route('update.feature.category') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                @for ($i = 0; $i < 3; $i++)
                    @php
                        $current = $featureCategories->where('order', $i + 1)->first();
                        $heading = match($i) {
                            0 => 'Slot 1 (Large - Left)',
                            1 => 'Slot 2 (Small - Top Right)',
                            2 => 'Slot 3 (Small - Bottom Right)',
                            default => 'Slot ' . ($i + 1)
                        };
                        $sizeRecommended = match($i) {
                            0 => 'Recommended Size: 600x800px',
                            1 => 'Recommended Size: 600x390px',
                            2 => 'Recommended Size: 600x390px',
                            default => ''
                        };
                    @endphp
                    <div class="col-md-4 mb-4">
                        <div class="card p-3 border">
                            <h5 class="card-title">{{ $heading }}</h5>
                            <p class="text-muted small">{{ $sizeRecommended }}</p>

                            <div class="form-group mb-3">
                                <label>Select Subcategory</label>
                                <select name="slots[{{ $i }}][subcategory_id]" class="form-control" required>
                                    <option value="">Select Subcategory</option>
                                    @foreach ($subcategories as $sub)
                                        <option value="{{ $sub->id }}" {{ ($current && $current->subcategory_id == $sub->id) ? 'selected' : '' }}>
                                            {{ $sub->proSubCat_name }} ({{ $sub->main_Cat }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Banner Image</label>
                                <input type="file" name="slots[{{ $i }}][banner_image]" class="form-control-file" accept="image/*">
                            </div>

                            @if ($current && $current->banner_image)
                                <div class="mt-2">
                                    <label>Current Image:</label><br>
                                    <img src="{{ asset('uploads/' . $current->banner_image) }}" alt="Current Banner" class="img-fluid rounded" style="max-height: 150px;">
                                </div>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">Update Categories</button>
            </div>
        </form>
    </div>
</div>
@endsection
