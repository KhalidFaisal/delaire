@extends('backend.layout.template')

@section('body-content')
<style>
    #searchResults {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        max-height: 300px;
        overflow-y: auto;
    }
    #searchResults .list-group-item {
        border-left: none;
        border-right: none;
    }
    #searchResults .list-group-item:first-child {
        border-top: none;
    }
    #searchResults .list-group-item:last-child {
        border-bottom: none;
    }
    #searchResults .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>Create New Order</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Orders</li>
                    <li class="breadcrumb-item active">Create Order</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <form action="{{ route('admin.orders.store') }}" method="POST" id="createOrderForm">
        @csrf
        <div class="row">
            <!-- Customer Information -->
            <div class="col-xl-4 col-md-12 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="shipping_name" required placeholder="Full Name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="shipping_phone" required placeholder="Phone Number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email (Optional)</label>
                            <input class="form-control" type="email" name="shipping_email" placeholder="Email Address">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address (Optional)</label>
                            <textarea class="form-control" name="shipping_address" rows="3" placeholder="Full Address"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">City (Optional)</label>
                            <input class="form-control" type="text" name="shipping_city" placeholder="City">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Zip Code (Optional)</label>
                            <input class="form-control" type="text" name="shipping_zip" placeholder="Zip Code">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Order Notes</label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="Any special instructions..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Selection -->
            <div class="col-xl-8 col-md-12 box-col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Order Items</h5>
                    </div>
                    <div class="card-body">
                        <!-- Product Search -->
                        <div class="mb-4 position-relative">
                            <label class="form-label">Search Product</label>
                            <input type="text" class="form-control" id="productSearch" placeholder="Type name to search...">
                            <div id="searchResults" class="list-group position-absolute w-100 mt-1" style="z-index: 1000; display: none;"></div>
                        </div>

                        <!-- Items Table -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th width="100">Price</th>
                                        <th width="120">Quantity</th>
                                        <th width="120">Total</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="orderItems">
                                    <tr id="emptyRow">
                                        <td colspan="5" class="text-center text-muted">No items added yet.</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Subtotal:</th>
                                        <th colspan="2">৳<span id="subtotalDisplay">0.00</span></th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">Delivery Charge:</td>
                                        <td colspan="2"><input type="number" name="delivery_charge" class="form-control form-control-sm" value="0" min="0" onchange="calculateTotal()"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Grand Total:</th>
                                        <th colspan="2">৳<span id="totalDisplay">0.00</span></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Create Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
    let subtotal = 0;
    let itemCount = 0;

    // Product Search
    $('#productSearch').on('keyup', function() {
        let query = $(this).val();
        if (query.length > 2) {
            $.ajax({
                url: "{{ route('product.search.ajax') }}",
                type: "GET",
                data: { query: query },
                success: function(data) {
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(product => {
                            // Escape single quotes for the data attribute
                            let productData = JSON.stringify(product).replace(/'/g, "&apos;");
                            
                            html += `<a href="#" class="list-group-item list-group-item-action product-item" data-product='${productData}'>
                                <div class="d-flex align-items-center">
                                    <img src="${product.image}" width="40" class="me-2 rounded">
                                    <div>
                                        <h6 class="mb-0">${product.title}</h6>
                                        <small class="text-muted">৳${product.price}</small>
                                    </div>
                                </div>
                            </a>`;
                        });
                        $('#searchResults').html(html).show();
                    } else {
                        $('#searchResults').html('<div class="list-group-item text-muted">No products found</div>').show();
                    }
                }
            });
        } else {
            $('#searchResults').hide();
        }
    });

    // Handle Product Click (Event Delegation)
    $(document).on('click', '.product-item', function(e) {
        e.preventDefault();
        let product = $(this).data('product');
        addProduct(product);
    });

    // Hide search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#productSearch, #searchResults').length) {
            $('#searchResults').hide();
        }
    });

    // Add Product to Table
    window.addProduct = function(product) {
        $('#searchResults').hide();
        $('#productSearch').val('');
        $('#emptyRow').hide();
        $('#submitBtn').prop('disabled', false);
        
        // Use a unique ID based on product ID and random string to allow multiple entries (e.g. different sizes)
        // or just restrict to one entry per size later.
        // For now, simple unique row ID.
        let rowId = 'item-' + itemCount;

        let sizeHtml = '';
        if (product.sizes && product.sizes.length > 0) {
            sizeHtml = `<select name="items[${itemCount}][size]" class="form-select form-select-sm" required>
                <option value="">Select Size</option>`;
            product.sizes.forEach(s => {
                sizeHtml += `<option value="${s.size}">${s.size} (Stock: ${s.stock})</option>`;
            });
            sizeHtml += `</select>`;
        } else {
             sizeHtml = '<span class="text-muted">N/A</span>';
        }

        let row = `
            <tr id="${rowId}">
                <td>
                    <div class="d-flex align-items-center">
                        <img src="${product.image}" width="40" class="me-2 rounded">
                        <div class="ms-2">
                            <span>${product.title}</span>
                            <input type="hidden" name="items[${itemCount}][product_id]" value="${product.id}">
                            <div class="mt-1">
                                ${sizeHtml}
                            </div>
                        </div>
                    </div>
                </td>
                <td>৳${product.price}</td>
                <td>
                    <input type="number" name="items[${itemCount}][qty]" class="form-control form-control-sm qty-input" value="1" min="1" onchange="updateLineTotal('${rowId}', ${product.price})">
                </td>
                <td>৳<span class="line-total">${product.price}</span></td>
                <td>
                    <button type="button" class="btn btn-xs btn-danger" onclick="removeItem('${rowId}')"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `;

        $('#orderItems').append(row);
        itemCount++;
        calculateTotal();
    };

    // Remove Item
    window.removeItem = function(rowId) {
        $(`#${rowId}`).remove();
        if ($('#orderItems tr').length === 1) { // counting emptyRow
             $('#emptyRow').show();
             $('#submitBtn').prop('disabled', true);
        }
        calculateTotal();
    };

    // Update Line Total
    window.updateLineTotal = function(rowId, price) {
        let qty = parseInt($(`#${rowId} .qty-input`).val());
        if (qty < 1 || isNaN(qty)) {
            qty = 1;
            $(`#${rowId} .qty-input`).val(1);
        }
        let total = qty * price;
        $(`#${rowId} .line-total`).text(total.toFixed(2));
        calculateTotal();
    };

    // Calculate Grand Total
    window.calculateTotal = function() {
        let total = 0;
        $('.line-total').each(function() {
            total += parseFloat($(this).text());
        });
        subtotal = total;
        $('#subtotalDisplay').text(subtotal.toFixed(2));

        let delivery = parseFloat($('input[name="delivery_charge"]').val()) || 0;
        let grandTotal = subtotal + delivery;
        $('#totalDisplay').text(grandTotal.toFixed(2));
    };
</script>
@endsection
