<style>
        .qty-control {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f5f5f5;
    border-radius: 30px;
    padding: 4px 8px;
}

.qty-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    background: #fff;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 1px 4px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
}

.qty-btn:hover {
    background: #000;
    color: #ff11a4ff;
}

.qty-value {
    min-width: 24px;
    text-align: center;
    font-weight: 600;
    font-size: 14px;
}

/* Mobile optimization */
@media (max-width: 576px) {
    .qty-btn {
        width: 28px;
        height: 28px;
        font-size: 16px;
    }
}


</style>

<div class="offcanvas offcanvas-end" tabindex="-1" id="drawer-cart">
            <div class="offcanvas-header border-btm-black">
                <h5 class="cart-drawer-heading text_16">your Cart (04)</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                <div class="cart-content-area d-flex justify-content-between flex-column">
                    <div class="minicart-loop custom-scrollbar" id="drawerCartItems"></div>
                    <div class="minicart-footer">
                        <div class="minicart-calc-area">
                            <div class="minicart-calc d-flex align-items-center justify-content-between">
                                <span class="cart-subprice" id="cartSubtotal">$0.00</span>
                            </div>
                            <p class="cart-taxes text-center my-4">Taxes and shipping will be calculated at checkout.
                            </p>
                        </div>
                        <div class="minicart-btn-area d-flex align-items-center justify-content-between">
                            <a href="cart.html" class="minicart-btn btn-secondary">View Cart</a>
                            <a href="{{ route('checkout.index') }}" class="minicart-btn btn-primary">Checkout</a>
                        </div>
                    </div>
                </div>
                <div class="cart-empty-area text-center py-5 d-none">
                    <div class="cart-empty-icon pb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                            >
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M16 16s-1.5-2-4-2-4 2-4 2"></path>
                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                        </svg>
                    </div>
                    <p class="cart-empty">You have no items in your cart</p>
                </div>
            </div>
        </div>

        <script>
function getCart() {
    return JSON.parse(localStorage.getItem('drawer_cart')) || {};
}

function saveCart(cart) {
    localStorage.setItem('drawer_cart', JSON.stringify(cart));
    renderDrawerCart();  
}

function renderDrawerCart() {
    let cart = getCart();
    let html = '';
    let subtotal = 0;
    let count = 0;

    for (let id in cart) {
        let item = cart[id];
        subtotal += item.price * item.qty;
        count += item.qty;

        html += `
        <div class="minicart-item d-flex">
            <div class="mini-img-wrapper">
                <img class="mini-img" src="${item.img}">
            </div>
            <div class="product-info">
                <h2 class="product-title">${item.title}</h2>
                <div class="misc d-flex justify-content-between">
                    <div class="qty-control">
                        <button class="qty-btn" onclick="changeQty('${id}', -1)">−</button>
                        <span class="qty-value">${item.qty}</span>
                        <button class="qty-btn" onclick="changeQty('${id}', 1)">+</button>
                    </div>
                    <div>
                        <div>৳${item.price}</div>
                        <a href="#" style="color:Grey;" onclick="removeItem('${id}')">Remove</a>
                    </div>
                </div>
            </div>
        </div>`;
    }

    document.getElementById('drawerCartItems').innerHTML = html || 
        '<p class="text-center py-5">Cart is empty</p>';

    document.getElementById('cartSubtotal').innerText = `৳${subtotal.toFixed(2)}`;
    document.querySelector('.cart-drawer-heading').innerText = `Your Cart (${count})`;

    // Update header cart count
    const headerCartCount = document.getElementById('cart-count');
    if (headerCartCount) {
        headerCartCount.innerText = count;
    }
}

function changeQty(id, change) {
    let cart = getCart();
    let item = cart[id];

    let newQty = item.qty + change;
    if (newQty < 1 || newQty > item.stock) return;

    item.qty = newQty;
    saveCart(cart);
}

function removeItem(id) {
    let cart = getCart();
    delete cart[id];
    saveCart(cart);
}

document.addEventListener('DOMContentLoaded', function() {
    renderDrawerCart();

    @if(session('order_placed'))
        localStorage.removeItem('drawer_cart');
        renderDrawerCart(); // Will render empty state
        // Also update header badge directly just in case logic is split
        const headerCartCount = document.getElementById('cart-count');
        if (headerCartCount) headerCartCount.innerText = '0';
    @endif
});

// Global Cart Event Listener (handles all .add-to-cart clicks)
document.addEventListener('click', function (e) {
    if (!e.target.classList.contains('add-to-cart') && !e.target.closest('.add-to-cart')) return;

    // Prevent default if it's a link (though usually buttons)
    // e.preventDefault(); 

    let btn = e.target.closest('.add-to-cart') || e.target;
    let cart = getCart();
    let id = btn.dataset.id;
    
    if(!id) return; // Safety check

    if (!cart[id]) {
        cart[id] = {
            id: id,
            title: btn.dataset.title,
            price: parseFloat(btn.dataset.price),
            qty: 1,
            stock: parseInt(btn.dataset.stock || 100), // Default stock if missing
            img: btn.dataset.img
        };
    } else {
        if (cart[id].qty >= cart[id].stock) {
            alert('Stock limit reached');
            return;
        }
        cart[id].qty++;
    }

    saveCart(cart);
    
    // Show notification if function exists
    if(typeof showCartNotification === 'function') {
        showCartNotification();
    } else if(typeof showNotification === 'function') {
        // Fallback to local name if different
        showNotification();
    } else {
        // Fallback if no notification function
        let drawer = document.getElementById('drawer-cart');
        if(drawer) new bootstrap.Offcanvas(drawer).show();
    }
});

// Dummy function to prevent inline onclick errors if they exist
window.addToCart = function(id) {
    // Logic handled by event listener
    console.log('addToCart called via inline handler - handled by event listener');
};
</script>