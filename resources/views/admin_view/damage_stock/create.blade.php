@extends('backend.layout.template')
@section('title')
    Record Damage Stock
@endsection
@section('body-content')
<div class="container card">
    <div class="content-container p-4">
        <h3 class="text-center">Record Damage Stock</h3><br>

        <form action="{{ route('admin.damage.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="product_id">Select Product</label>
                    <select class="form-control select2" id="product_id" name="product_id" required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->pro_title }} (Stock: {{ $product->pro_qty }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="product_size_id">Size (Optional)</label>
                    <select class="form-control" id="product_size_id" name="product_size_id" disabled>
                        <option value="">-- Select Product First --</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="lot_number">Lot / Batch Number (Optional)</label>
                    <input type="text" class="form-control" id="lot_number" name="lot_number" placeholder="If known">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="entry_date">Date</label>
                    <input type="date" class="form-control" id="entry_date" name="entry_date" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="quantity">Quantity (Damaged)</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    <small class="text-muted">Enter positive number. It will be deducted from stock.</small>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="note">Note / Reason</label>
                    <textarea class="form-control" id="note" name="note" rows="3" required placeholder="e.g. Broken in transit, Expired, etc."></textarea>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-danger">Record Damage</button>
                <a href="{{ route('admin.damage.stock') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    document.getElementById('product_id').addEventListener('change', function() {
        var productId = this.value;
        var sizeSelect = document.getElementById('product_size_id');
        
        // Reset size select
        sizeSelect.innerHTML = '<option value="">-- Select Size --</option>';
        sizeSelect.disabled = true;

        if(productId) {
            fetch("{{ url('/inventory/get-sizes') }}/" + productId)
                .then(response => response.json())
                .then(data => {
                    if(data.length > 0) {
                        sizeSelect.disabled = false;
                        data.forEach(size => {
                            var option = document.createElement('option');
                            option.value = size.id;
                            option.text = size.size + ' (Stock: ' + size.stock + ')';
                            sizeSelect.appendChild(option);
                        });
                    } else {
                         sizeSelect.innerHTML = '<option value="">No sizes for this product</option>';
                         sizeSelect.disabled = true;
                    }
                });
        }
    });
</script>
@endsection
