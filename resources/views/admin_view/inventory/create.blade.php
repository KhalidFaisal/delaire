@extends('backend.layout.template')
@section('title')
    Add New Stock
@endsection
@section('body-content')
<div class="container card">
    <div class="content-container p-4">
        <h3 class="text-center">Add New Stock</h3><br>

        <form action="{{ route('admin.inventory.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                 <div class="col-md-4">
                    <label for="entry_date">Stock Date</label>
                    <input type="date" class="form-control" id="entry_date" name="entry_date" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <label for="type">Stock Type</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="purchase" selected>Purchase (New Stock)</option>
                        <option value="return">Return (Restock)</option>
                        <option value="adjustment">Adjustment</option>
                    </select>
                </div>
                 <div class="col-md-4">
                    <label for="lot_number">Stock Lot / Batch Number</label>
                    <input type="text" class="form-control" id="lot_number" name="lot_number">
                </div>
            </div>

            <div class="p-3 mb-3" style="background-color: #f8f9fa; border-radius: 5px; border: 1px solid #e9ecef;">
                <div class="row align-items-end">
                    <div class="col-md-9">
                        <label>Select Product to Add Stock</label>
                        <select class="form-control select2" id="masterProductSelect" style="width: 100%;">
                            <option value="">-- Select Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-image="{{ asset('uploads/'.$product->pro_img1) }}" data-stock="{{ $product->pro_qty }}" data-model="{{ $product->pro_model }}">
                                    {{ $product->pro_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success btn-block" id="addSizeBtn">
                            <i class="fa fa-plus"></i> Add Size For Product
                        </button>
                    </div>
                </div>
            </div>

            <table class="table table-bordered" id="stockTable">
                <thead>
                    <tr>
                        <th width="30%">Product</th>
                        <th width="20%">Size</th>
                        <th width="15%">Current Stock</th>
                        <th width="15%">Quantity</th>
                        <th width="25%">Note</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Rows will be added dynamically --}}
                </tbody>
            </table>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Save All Stock</button>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .img-flag {
        border-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let rowCount = 0;
    let productSizes = {}; // Cache for product sizes

    $(document).ready(function() {
        initializeSelect2($('#masterProductSelect'));
    });

    function initializeSelect2(element) {
        element.select2({
            templateResult: formatProduct,
            templateSelection: formatProductSelection,
            width: '100%'
        });
    }

    function formatProduct (product) {
        if (!product.id) {
            return product.text;
        }
        
        var $product = $(
            '<span><img src="' + $(product.element).data('image') + '" class="img-flag" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;" /> ' + product.text + ' <small class="text-muted">(Model: ' + $(product.element).data('model') + ', Stock: ' + $(product.element).data('stock') + ')</small></span>'
        );
        return $product;
    }

    function formatProductSelection (product) {
        if (!product.id) {
            return product.text;
        }
         var $product = $(
            '<span><img src="' + $(product.element).data('image') + '" class="img-flag" style="width: 30px; height: 30px; object-fit: cover; margin-right: 10px;" /> ' + product.text + '</span>'
        );
        return $product;
    }

    // When master product changes, fetch sizes if needed
    $('#masterProductSelect').on('change', function() {
        const productId = $(this).val();
        if(productId && !productSizes[productId]) {
            fetchSizes(productId);
        }
    });

    function fetchSizes(productId) {
         return fetch("{{ url('/inventory/get-sizes') }}/" + productId)
                .then(response => response.json())
                .then(data => {
                    productSizes[productId] = data;
                    return data;
                });
    }

    document.getElementById('addSizeBtn').addEventListener('click', function() {
        const masterSelect = $('#masterProductSelect');
        const productId = masterSelect.val();

        if (!productId) {
            alert("Please select a product first.");
            return;
        }

        const productData = masterSelect.select2('data')[0];
        // Ensure we have size data
        if (!productSizes[productId]) {
            // Need to fetch first? Should have been fetched on change, but just in case
             fetchSizes(productId).then(() => {
                 addRow(productId, productData);
             });
        } else {
             addRow(productId, productData);
        }
    });

    function addRow(productId, productData) {
        const tableBody = document.querySelector('#stockTable tbody');
        const newRow = document.createElement('tr');
        
        // Construct the static product display HTML
        const productHtml = `
            <div class="d-flex align-items-center">
                <img src="${$(productData.element).data('image')}" class="img-flag" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;" />
                <div>
                     <strong>${productData.text}</strong><br>
                     <small class="text-muted">Model: ${$(productData.element).data('model')}</small>
                </div>
                <input type="hidden" class="product-id-input" name="items[${rowCount}][product_id]" value="${productId}">
            </div>
        `;

        // Create datalist options
        let datalistOptions = '';
        if (productSizes[productId]) {
            productSizes[productId].forEach(size => {
                datalistOptions += `<option value="${size.size}">`;
            });
        }
        
        newRow.innerHTML = `
            <td>
                ${productHtml}
            </td>
            <td>
                <input type="text" class="form-control size-input" name="items[${rowCount}][size]" placeholder="Enter Size" list="sizeList_${rowCount}" autocomplete="off">
                <datalist id="sizeList_${rowCount}">
                    ${datalistOptions}
                </datalist>
            </td>
             <td>
                 <input type="text" class="form-control current-stock" readonly value="0">
            </td>
            <td>
                 <input type="number" class="form-control" name="items[${rowCount}][quantity]" min="1" required>
            </td>
            <td>
                <input type="text" class="form-control" name="items[${rowCount}][note]">
            </td>
            <td>
                <button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button>
            </td>
        `;
        
        tableBody.appendChild(newRow);
        
        // Focus on the size input of new row
        $(newRow).find('.size-input').focus();

        rowCount++;
    }

    document.querySelector('#stockTable').addEventListener('click', function(e) {
        if(e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    // Event delegation for input event on size-input (simple text input)
    $(document).on('input', '.size-input', function() {
        const row = $(this).closest('tr');
        const productId = row.find('.product-id-input').val(); // Get ID from hidden input
        const enteredSize = $(this).val().trim();
        const stockInput = row.find('.current-stock');

        // Reset stock (will set if match found)
        stockInput.val(0);

        if (productId && productSizes[productId]) {
            const matchedSize = productSizes[productId].find(s => s.size.toLowerCase() === enteredSize.toLowerCase());
            if (matchedSize) {
                stockInput.val(matchedSize.stock);
            }
        }
    });
</script>
@endsection
