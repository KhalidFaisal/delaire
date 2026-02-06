<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5 mb-5">
    <h2>Checkout</h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card p-4">
                <h4>Shipping Details</h4>
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form id="checkoutForm" method="POST" action="{{ route('checkout.store') }}">
                    @csrf
                    <input type="hidden" name="cart_data" id="cart-data">

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="shipping_name" class="form-control" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="shipping_email" class="form-control" value="{{ auth()->user()->email }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="shipping_phone" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="shipping_address" class="form-control" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>City</label>
                            <input type="text" name="shipping_city" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Zip Code</label>
                            <input type="text" name="shipping_zip" class="form-control" required>
                        </div>
                    </div>

                    <h4 class="mt-4">Payment Method</h4>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                        <label class="form-check-label" for="cod">
                            Cash on Delivery
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4 w-100">Place Order (COD)</button>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <h4>Order Summary</h4>
                <div id="checkout-cart-items"></div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total</strong>
                    <strong id="checkout-total">৳0.00</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cart = JSON.parse(localStorage.getItem('drawer_cart')) || {};
        const cartDataInput = document.getElementById('cart-data');
        const cartItemsContainer = document.getElementById('checkout-cart-items');
        const totalElement = document.getElementById('checkout-total');
        
        let subtotal = 0;
        let html = '';

        if (Object.keys(cart).length === 0) {
            cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
            document.querySelector('button[type="submit"]').disabled = true;
            return;
        }

        // Prepare data for backend
        cartDataInput.value = JSON.stringify(cart);

        for (let id in cart) {
            let item = cart[id];
            subtotal += item.price * item.qty;
            
            html += `
            <div class="d-flex justify-content-between mb-2">
                <span>${item.title} x ${item.qty}</span>
                <span>৳${(item.price * item.qty).toFixed(2)}</span>
            </div>`;
        }

        cartItemsContainer.innerHTML = html;
        totalElement.innerText = `৳${subtotal.toFixed(2)}`;

        // Clear cart on successful submission (optional: handled by backend redirection mostly, 
        // but nice to check if we are on success page logic. 
        // For now, we assume user flow. We can clear localStorage if session flash success exists in index view maybe?
        // Actually, let's keep it simple. If order success, backend redirects. 
        // We'll add a script on 'user_orders' or 'thank you' page to clear cart if new order placed.
        // Or simply here:
        const form = document.getElementById('checkoutForm');
        form.addEventListener('submit', function() {
             // We don't clear here immediately in case submission fails. 
             // Ideally backend returns JSON and we clear on success. 
             // But valid constraint: if form submits, we assume success or page reload.
             // If redirect happens, existing page is gone.
             // We can check a flag 'order_placed' from session in the dashboard/orders page?
        });
    });
</script>
</body>
</html>