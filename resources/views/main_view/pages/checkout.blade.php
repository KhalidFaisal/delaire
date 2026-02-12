<!doctype html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>Checkout - {{ $portfolio->company_name ?? 'Pinkush' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Checkout - {{ $portfolio->company_name ?? 'Pinkush' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ isset($portfolio->favicon) ? asset($portfolio->favicon) : asset('main_view/assets/img/favicon.png')}}" type="image/x-icon">
    @include('main_view.include.css')
    
    <style>
        /* Custom Styles for Checkout */
        .checkout-section {
            background-color: #f8f9fa;
        }
        .checkout-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-bottom: 20px;
            border: 1px solid #eee;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(222, 46, 121, 0.25); /* Adjust based on primary color usage if needed, or rely on variable */
        }
        .section-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--heading-color);
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }
        .summary-sticky {
            position: sticky;
            top: 100px;
        }
        .payment-method-card {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-method-card.active {
            border-color: var(--primary-color);
            background-color: rgba(var(--primary-rgb), 0.05); /* Needs RGB var or fallback */
            background-color: #fff5f9; /* Fallback light pink/primary tint */
        }
        .cart-item-row {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .cart-item-row:last-child {
            border-bottom: none;
        }
        .total-row {
            font-size: 1.1rem;
            border-top: 2px solid #eee;
            padding-top: 15px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="body-wrapper">
        @include('main_view.include.header')

        <main id="MainContent" class="content-for-layout">
            
            <!-- Breadcrumb / Header Area -->
            <div class="checkout-header py-5 bg-white border-bottom">
                <div class="container">
                    <h1 class="text-center display-5 fw-bold" style="color: var(--heading-color);">Checkout</h1>
                    <p class="text-center text-muted">Complete your order</p>
                </div>
            </div>

            <div class="checkout-section py-5">
                <div class="container">
                    <div class="row">
                        <!-- Left Column: Shipping & Payment -->
                        <div class="col-lg-8 col-md-12">
                            <form id="checkoutForm" method="POST" action="{{ route('checkout.store') }}">
                                @csrf
                                <input type="hidden" name="cart_data" id="cart-data">
                                <input type="hidden" name="promo_code" id="applied-promo-code">

                                <!-- Shipping Details -->
                                <div class="checkout-card">
                                    <h4 class="section-title">Shipping Details</h4>
                                    
                                    @if(session('error'))
                                        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                                    @endif

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="shipping_name" class="form-control" value="{{ auth()->user()->name }}" required placeholder="Ex: John Doe">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" name="shipping_email" class="form-control" value="{{ auth()->user()->email }}" required placeholder="Ex: john@example.com">
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" name="shipping_phone" class="form-control" required placeholder="Ex: 017xxxxxxxx">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Shipping Address <span class="text-danger">*</span></label>
                                        <textarea name="shipping_address" class="form-control" rows="3" required placeholder="Enter full address"></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label fw-bold">City <span class="text-danger">*</span></label>
                                            <input type="text" name="shipping_city" class="form-control" required placeholder="Ex: Dhaka">
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label fw-bold">Zip Code <span class="text-danger">*</span></label>
                                            <input type="text" name="shipping_zip" class="form-control" required placeholder="Ex: 1212">
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="checkout-card">
                                    <h4 class="section-title">Payment Method</h4>
                                    <div class="payment-options">
                                        <div class="payment-method-card active d-flex align-items-center mb-3">
                                            <input class="form-check-input me-3" type="radio" name="payment_method" id="cod" value="COD" checked style="width: 1.2rem; height: 1.2rem;">
                                            <div>
                                                <label class="form-check-label fw-bold d-block" for="cod">
                                                    Cash on Delivery
                                                </label>
                                                <small class="text-muted">Pay with cash upon delivery.</small>
                                            </div>
                                        </div>
                                        <!-- Placeholder for future payment methods (e.g., BKash, Card) -->
                                        <!-- 
                                        <div class="payment-method-card d-flex align-items-center text-muted" style="opacity: 0.6; cursor: not-allowed;">
                                            <input class="form-check-input me-3" type="radio" name="payment_method" id="online" value="ONLINE" disabled>
                                            <div>
                                                <label class="form-check-label fw-bold d-block" for="online">
                                                    Online Payment (Coming Soon)
                                                </label>
                                            </div>
                                        </div>
                                        -->
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <div id="checkout-suggestions" class="checkout-card mb-4" style="background: #fff; padding: 20px 20px 10px 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04);">
                                <h5 class="mb-4" style="font-size: 0.9rem; font-weight: 700; color: var(--primary-color); text-transform: uppercase; letter-spacing: 0.5px;">You might also like</h5>
                                @php
                                    // Fetch 3 random suggestions containing images
                                    $suggestedProducts = \App\Models\Product::whereNotNull('pro_img1')->inRandomOrder()->take(3)->get();
                                @endphp
                                @foreach($suggestedProducts as $s_product)
                                <div class="suggestion-item d-flex align-items-center mb-3 pb-3 {{ $loop->last ? '' : 'border-bottom' }}" 
                                     id="suggestion-{{ $s_product->id }}" 
                                     data-product-id="{{ $s_product->id }}"
                                     style="border-color: rgba(0,0,0,0.05) !important;">
                                    
                                    <div class="position-relative me-3">
                                        <img src="{{ asset('uploads/'. $s_product->pro_img1) }}" alt="{{ $s_product->pro_title }}" 
                                             style="width: 56px; height: 56px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                    
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="mb-0 text-truncate fw-medium" style="font-size: 0.9rem; color: #2d3748; max-width: 140px;" title="{{ $s_product->pro_title }}">{{ $s_product->pro_title }}</h6>
                                            <span class="fw-bold" style="font-size: 0.9rem; color: var(--primary-color);">৳{{ $s_product->final_price }}</span>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            @if($s_product->sizes->count() > 0)
                                                <select class="form-select form-select-sm product-size-select me-2" 
                                                        id="size-select-{{ $s_product->id }}" 
                                                        style="max-width: 100px; font-size: 0.75rem; border-color: #e2e8f0; border-radius: 6px; cursor: pointer; background-color: #f8fafc; padding-top: 2px; padding-bottom: 2px;">
                                                    <option value="" selected disabled>Size</option>
                                                    @foreach($s_product->sizes as $size)
                                                        @if($size->stock > 0)
                                                            <option value="{{ $size->size }}" data-stock="{{ $size->stock }}">{{ $size->size }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="hidden" id="size-select-{{ $s_product->id }}" value="">
                                                <span class="text-muted small" style="font-size: 0.75rem;">One Size</span>
                                            @endif

                                            <button class="btn btn-sm add-suggestion-btn d-flex align-items-center justify-content-center ms-auto" 
                                                    style="background-color: var(--primary-color); border: none; color: #fff; width: 30px; height: 30px; border-radius: 6px; transition: opacity 0.2s;"
                                                    onmouseover="this.style.opacity='0.9'" 
                                                    onmouseout="this.style.opacity='1'"
                                                    data-id="{{ $s_product->id }}"
                                                    data-title="{{ $s_product->pro_title }}"
                                                    data-price="{{ $s_product->final_price }}"
                                                    data-img="{{ asset('uploads/'.$s_product->pro_img1) }}"
                                                    data-stock="{{ $s_product->sizes->count() > 0 ? 0 : $s_product->pro_qty }}"
                                                    data-has-sizes="{{ $s_product->sizes->count() > 0 ? 'true' : 'false' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                  <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="checkout-card summary-sticky">
                                <h4 class="section-title">Order Summary</h4>
                                
                                <div id="checkout-cart-items" class="mb-4">
                                    <!-- Cart items injected via JS -->
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>

                                <div id="checkout-total"></div>

                                <!-- Promo Code Section -->
                                <div class="mt-4 pt-4 border-top">
                                    <div class="coupon-section">
                                         <!-- simplified styling -->
                                        <div class="input-group input-group-sm">
                                            <input type="text" id="promo-code-input" class="form-control" placeholder="Promo code">
                                            <button class="btn btn-dark" type="button" id="apply-promo-btn">Apply</button>
                                        </div>
                                        <div id="promo-message" class="small mt-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('main_view.include.footer')
        @include('main_view.include.drawermenu')
        @include('main_view.include.drawercart')
        @include('main_view.include.script')
        <script src="{{asset('main_view/assets/js/main.js')}}"></script>
        <script src="{{asset('main_view/assets/js/vendor.js')}}"></script>
        
        <!-- Page Specific Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cartDataInput = document.getElementById('cart-data');
                const cartItemsContainer = document.getElementById('checkout-cart-items');
                const totalElement = document.getElementById('checkout-total');
                const submitButton = document.querySelector('button[type="submit"]');
                
                // Function to get cart (reuse logic or rely on localStorage directly)
                function getLocalCart() {
                    return JSON.parse(localStorage.getItem('drawer_cart')) || {};
                }

                function saveLocalCart(cart) {
                    localStorage.setItem('drawer_cart', JSON.stringify(cart));
                    // Trigger global render if available is handled by checkout render
                    // We don't necessarily need global drawer update as user is on checkout
                }

                function renderCheckout() {
                    const cart = getLocalCart();
                    let subtotal = 0;
                    let html = '';
                    
                    // Filter suggestions: Hide if already in cart
                    // Note: cart keys might be ID or ID-Size.
                    // If ANY variant of product ID is in cart, maybe hide suggestion? 
                    // Or only if specific size? Usually simplified: if product ID in cart, hide it.
                    const cartProductIds = new Set();
                    for(let key in cart) {
                        // key is either "123" or "123-Size"
                        let pid = cart[key].id || key.split('-')[0];
                        cartProductIds.add(String(pid));
                    }
                    
                    document.querySelectorAll('.suggestion-item').forEach(item => {
                        if(cartProductIds.has(String(item.dataset.productId))) {
                            item.style.display = 'none';
                        } else {
                            item.style.display = 'flex'; // Restore if removed
                        }
                    });

                    // Check for empty cart
                    if (Object.keys(cart).length === 0) {
                        cartItemsContainer.innerHTML = '<div class="alert alert-warning text-center py-2 small">Cart is empty.</div>';
                        if(submitButton) {
                            submitButton.disabled = true;
                            // submitButton.textContent = 'Cart is Empty';
                        }
                        totalElement.innerHTML = '';
                        cartDataInput.value = '{}'; 
                        return;
                    }
                    
                    if(submitButton) {
                         submitButton.disabled = false;
                        //  submitButton.textContent = 'Place Order';
                    }

                    // Prepare data for backend
                    cartDataInput.value = JSON.stringify(cart);

                    // Generate Cart Items HTML
                    for (let id in cart) {
                        let item = cart[id];
                        let lineTotal = item.price * item.qty;
                        subtotal += lineTotal;
                        
                        let imgHtml = '';
                        if(item.img) {
                             imgHtml = `<img src="${item.img}" alt="${item.title}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;" class="me-2 border">`;
                        }
                        
                        // Display Size if exists
                        let sizeHtml = '';
                        if(item.size) {
                            sizeHtml = `<div class="text-muted" style="font-size: 0.75rem;">Size: ${item.size}</div>`;
                        }

                        html += `
                        <div class="cart-item-row d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                            <div class="d-flex align-items-center" style="max-width: 75%;">
                                ${imgHtml}
                                <div style="min-width:0;">
                                    <h6 class="mb-0 text-truncate title-text" style="font-size: 0.9rem;" title="${item.title}">${item.title}</h6>
                                    ${sizeHtml}
                                    <div class="d-flex align-items-center mt-1">
                                         <small class="text-muted me-2" style="font-size: 0.8rem;">Qty: ${item.qty}</small>
                                         <button type="button" class="btn btn-link p-0 text-danger text-decoration-none remove-item-btn" style="font-size: 0.75rem;" data-id="${id}">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <span class="fw-medium" style="font-size: 0.9rem;">৳${lineTotal.toFixed(2)}</span>
                        </div>`;
                    }

                    cartItemsContainer.innerHTML = html;

                    let deliveryCharge = {{ \App\Models\GeneralSetting::first()->delivery_charge ?? 0 }};
                    updateTotalsHTML(subtotal, deliveryCharge);
                    
                    // Attach event listeners for remove buttons
                    document.querySelectorAll('.remove-item-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const idToRemove = this.getAttribute('data-id');
                            delete cart[idToRemove];
                            saveLocalCart(cart);
                            renderCheckout();
                            showNotification('Item removed', 'text-danger');
                        });
                    });
                }
                
                // Variables to track state
                let currentDiscount = 0;
                let currentPromoCode = null;

                function updateTotalsHTML(subtotal, deliveryCharge) {
                    let finalTotal = subtotal + deliveryCharge - currentDiscount;
                    if(finalTotal < 0) finalTotal = 0;

                    let discountHtml = '';
                    if(currentDiscount > 0) {
                        discountHtml = `
                        <div class="d-flex justify-content-between text-success mb-1" style="font-size: 0.9rem;">
                            <span>Discount (${currentPromoCode})</span>
                            <span>-৳${parseFloat(currentDiscount).toFixed(2)}</span>
                        </div>`;
                    }

                    // Check if submit button already exists in total area to avoid duplicates if re-rendering partials
                    // Actually we are replacing innerHTML of totalElement, so we can just append button there.
                    // But the form is outside. We need a button that triggers form submit.
                    
                    totalElement.innerHTML = `
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.9rem;">
                            <span class="text-muted">Subtotal</span>
                            <span>৳${subtotal.toFixed(2)}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.9rem;">
                            <span class="text-muted">Delivery Charge</span>
                            <span>৳${deliveryCharge.toFixed(2)}</span>
                        </div>
                        ${discountHtml}
                        <div class="total-row d-flex justify-content-between align-items-center mt-2 pt-2 border-top mb-3">
                            <strong style="font-size: 1rem;">Total</strong>
                            <strong style="color: var(--primary-color); font-size: 1.2rem;">৳${finalTotal.toFixed(2)}</strong>
                        </div>
                        
                        <button type="button" id="confirm-order-btn" class="btn btn-primary w-100 py-3 fw-bold text-uppercase" style="font-size: 1rem; letter-spacing: 0.5px;">
                            Confirm Order
                        </button>
                    `;
                    
                    // Bind click to real form submit
                    setTimeout(() => {
                        const confirmBtn = document.getElementById('confirm-order-btn');
                        if(confirmBtn) {
                            confirmBtn.addEventListener('click', function() {
                                document.getElementById('checkout-form').submit();
                            });
                        }
                    }, 0);
                }

                // Initial render
                renderCheckout();

                // Promo Code Logic
                const promoInput = document.getElementById('promo-code-input');
                const applyBtn = document.getElementById('apply-promo-btn');
                const promoMsg = document.getElementById('promo-message');
                // const appliedPromoInput = document.getElementById('applied-promo-code'); 

                if(applyBtn){
                    applyBtn.addEventListener('click', function() {
                        const code = promoInput.value.trim();
                        if(!code) {
                            promoMsg.innerHTML = '<span class="text-danger">Please enter a code.</span>';
                            return;
                        }

                        // Get current subtotal
                        const cart = getLocalCart();
                        let subtotal = 0;
                        for(let id in cart) subtotal += cart[id].price * cart[id].qty;

                        promoMsg.innerHTML = '<span class="text-info">Checking...</span>';
                        applyBtn.disabled = true;

                        fetch('{{ route("checkout.promo") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                promo_code: code,
                                cart_total: subtotal
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            applyBtn.disabled = false;
                            const appliedInput = document.getElementById('applied-promo-code');
                            if(data.valid) {
                                promoMsg.innerHTML = `<span class="text-success">${data.message}</span>`;
                                if(appliedInput) appliedInput.value = code;
                                
                                currentDiscount = data.discount;
                                currentPromoCode = code;
                                let deliveryCharge = {{ \App\Models\GeneralSetting::first()->delivery_charge ?? 0 }};
                                updateTotalsHTML(subtotal, deliveryCharge);
                                
                            } else {
                                promoMsg.innerHTML = `<span class="text-danger">${data.message}</span>`;
                                if(appliedInput) appliedInput.value = '';
                                currentDiscount = 0;
                                currentPromoCode = null;
                                let deliveryCharge = {{ \App\Models\GeneralSetting::first()->delivery_charge ?? 0 }};
                                updateTotalsHTML(subtotal, deliveryCharge);
                            }
                        })
                        .catch(err => {
                            applyBtn.disabled = false;
                            promoMsg.innerHTML = '<span class="text-danger">Error checking code.</span>';
                            console.error(err);
                        });
                    });
                }

                // Suggestion Add to Cart Logic
                document.querySelectorAll('.add-suggestion-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const hasSizes = this.dataset.hasSizes === 'true';
                        let size = null;
                        let maxStock = parseInt(this.dataset.stock);

                        if(hasSizes) {
                            const select = document.getElementById(`size-select-${id}`);
                            size = select.value;
                            if(!size) {
                                alert('Please select a size');
                                return;
                            }
                            // Get stock from selected option
                            const selectedOption = select.options[select.selectedIndex];
                            maxStock = parseInt(selectedOption.dataset.stock);
                        }

                        // Add to Cart Logic
                        let cart = getLocalCart();
                        const cartKey = hasSizes && size ? `${id}-${size}` : id;

                        if (!cart[cartKey]) {
                            cart[cartKey] = {
                                id: id,
                                title: this.dataset.title,
                                price: parseFloat(this.dataset.price),
                                qty: 1,
                                stock: maxStock,
                                img: this.dataset.img,
                                size: size
                            };
                        } else {
                            if (cart[cartKey].qty >= maxStock) {
                                alert('Stock limit reached');
                                return;
                            }
                            cart[cartKey].qty++;
                        }
                        
                        saveLocalCart(cart);
                        showNotification('Item added!', 'text-success'); 
                        renderCheckout(); 
                    });
                });
            });
            
             function showNotification(msg = 'Item added!', colorClass = 'text-success') {
                const box = document.getElementById('cart-notification');
                if(box) {
                    box.innerText = msg;
                    box.classList.remove('text-success', 'text-danger');
                    box.classList.add(colorClass); 
                    box.style.display = 'block';
                    setTimeout(() => { box.style.display = 'none'; }, 2000);
                } 
            }
        </script>
    </div>
</body>
</html>