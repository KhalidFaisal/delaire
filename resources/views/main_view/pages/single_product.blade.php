<!doctype html>
<html lang="en" class="no-js">
<head>
    <title>{{ $product->meta_title ?? $product->pro_title }} | {{ $portfolio->company_name ?? 'Pinkush' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $product->meta_description ?? Str::limit(strip_tags($product->pro_desc), 160) }}">
    <meta name="keywords" content="{{ $product->meta_keywords ?? '' }}">
    <link rel="shortcut icon" href="{{ isset($portfolio->favicon) ? asset($portfolio->favicon) : asset('main_view/assets/img/favicon.png')}}" type="image/x-icon">
    @include('main_view.include.css')
    <style>
        /* User Review & Top Section styles */
        .product-gallery-thumb { cursor: pointer; border: 1px solid #eee; transition: all 0.2s; }
        .product-gallery-thumb:hover, .product-gallery-thumb.active { border-color: var(--primary-color); }
        .size-option { cursor: pointer; border: 1px solid #ddd; padding: 5px 10px; margin-right: 5px; border-radius: 4px; display: inline-block; }
        .size-option.selected { background-color: var(--primary-color); color: #fff; border-color: var(--primary-color); }
        .review-card { border-bottom: 1px solid #f1f1f1; padding: 15px 0; }
        .star-rating { color: #fcc904; }
        .add-to-cart-btn { background-color: var(--primary-color); border-color: var(--primary-color); color: white; }
        .add-to-cart-btn:hover { background-color: #c02063; border-color: #c02063; }
        
        /* Minimalist Card CSS (from index) */
        .minimalist-card {
            border: 1px solid #f9f9f9;
            transition: all 0.3s ease;
            cursor: pointer;
            background: #fff;
            padding-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        .minimalist-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transform: translateY(-5px);
        }
        .wishlist-btn {
            z-index: 10;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            cursor: pointer;
        }
        .wishlist-btn:hover {
            background: #f8f8f8;
        }
        .wishlist-btn.active svg {
            fill: var(--primary-color);
            stroke: var(--primary-color);
        }
        .shop-now-btn {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s;
        }
        .minimalist-card:hover .shop-now-btn {
            opacity: 1;
            transform: translateY(0);
        }
        .product-img-wrapper {
            position: relative;
            overflow: hidden;
            padding-top: 100%; /* 1:1 Aspect Ratio */
        }
        .product-img-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .minimalist-card:hover .product-img-wrapper img {
            transform: scale(1.05);
        }
        
        /* Qty Control Styles */
        .qty-control {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f5f5f5;
            border-radius: 30px;
            padding: 4px 8px;
            width: fit-content;
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
            color: var(--primary-color);
        }

        .qty-value {
            min-width: 24px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="body-wrapper">
        @include('main_view.include.header')

        <main id="MainContent" class="content-for-layout">
            <div class="container mt-5 mb-5">
                <div class="row">
                    <!-- Product Images -->
                    <div class="col-md-6 mb-4">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <img id="main-product-image" class="img-fluid rounded w-100" src="{{ asset('uploads/'. $product->pro_img1) }}" alt="{{ $product->pro_title }}">
                            </div>
                            <!-- Thumbnails -->
                            <div class="col-4">
                                <img class="img-fluid rounded product-gallery-thumb active" src="{{ asset('uploads/'. $product->pro_img1) }}" onclick="updateMainImage(this)" alt="Thumb 1">
                            </div>
                            @if($product->pro_img2)
                            <div class="col-4">
                                <img class="img-fluid rounded product-gallery-thumb" src="{{ asset('uploads/'. $product->pro_img2) }}" onclick="updateMainImage(this)" alt="Thumb 2">
                            </div>
                            @endif
                            @if($product->pro_img3)
                            <div class="col-4">
                                <img class="img-fluid rounded product-gallery-thumb" src="{{ asset('uploads/'. $product->pro_img3) }}" onclick="updateMainImage(this)" alt="Thumb 3">
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="col-md-6">
                        <!-- Stock Badge -->
                        @if(($product->sizes->count() > 0 ? $product->sizes->sum('stock') : $product->pro_qty) > 0)
                            <span class="badge bg-success mb-2">In Stock</span>
                        @else
                            <span class="badge bg-danger mb-2">Out of Stock</span>
                        @endif

                        <h1 class="mb-2">{{ $product->pro_title }}</h1>
                        
                        <!-- Reviews -->
                        <div class="mb-3 d-flex align-items-center">
                            <div class="star-rating me-2" style="color: #fcc904;">
                                @php $rating = round($average_rating); @endphp
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= $rating) ★ @else ☆ @endif
                                @endfor
                            </div>
                            <span class="text-muted">({{ $review_count }} Reviews)</span>
                        </div>

                        <!-- Details -->
                        <p class="text-muted small mb-1"><strong>Brand:</strong> {{ $product->brand->brand_name ?? 'N/A' }}</p>
                        @if($product->pro_model)
                            <p class="text-muted small mb-1"><strong>Model:</strong> {{ $product->pro_model }}</p>
                        @endif
                        <div class="mb-4">
                            <br>
                            <p class="text-muted">
                                {!! $product->pro_short_desc ? nl2br(e($product->pro_short_desc)) : Str::limit($product->pro_desc, 150) !!}
                            </p>
                        </div>
                        <div class="mb-3 mt-3">
                            <h3 class="d-inline" style="color: var(--primary-color);">৳ {{ $product->final_price }}</h3>
                            @if($product->final_price < $product->adjusted_price)
                                <span class="text-muted text-decoration-line-through ms-2">৳ {{ $product->adjusted_price }}</span>
                            @endif
                        </div>

                        

                        <!-- Size Selection -->
                        @if($product->sizes->count() > 0)
                        <div class="mb-4">
                            <label class="d-block mb-2 font-weight-bold">Select Size:</label>
                            <div id="size-selector">
                                @foreach($product->sizes as $size)
                                    @if($size->stock > 0)
                                        <span class="size-option" onclick="selectSize(this, '{{ $size->size }}', {{ $size->stock }})">{{ $size->size }}</span>
                                    @endif
                                @endforeach
                            </div>
                            <input type="hidden" id="selected-size" value="">
                            <input type="hidden" id="selected-stock" value="0">
                        </div>
                        @else
                            <input type="hidden" id="selected-stock" value="{{ $product->pro_qty }}">
                        @endif

                        <!-- Actions -->
                        <div class="d-flex flex-column mb-4">
                            <div id="size-error" class="text-danger mb-2" style="display: none; font-weight: 600;">Please select a shoe size</div>
                            <div id="qty-error" class="text-danger mb-2" style="display: none; font-weight: 600;">Stock limit reached</div>
                            
                            <div class="d-flex align-items-center gap-3">
                                <!-- Styles copied from cart logic -->
                                <div class="qty-control">
                                    <button class="qty-btn" onclick="updateQty(-1)">−</button>
                                    <span class="qty-value" id="qty-display">1</span>
                                    <button class="qty-btn" onclick="updateQty(1)">+</button>
                                </div>
                                <input type="hidden" id="product-qty" value="1">
                                
                                <button id="single-page-add-to-cart" class="btn btn-primary btn-lg flex-grow-1"
                                    data-id="{{ $product->id }}"
                                    data-title="{{ $product->pro_title }}"
                                    data-price="{{ $product->final_price }}"
                                    data-stock="{{ $product->sizes->count() > 0 ? 0 : $product->pro_qty }}"
                                    data-has-sizes="{{ $product->sizes->count() > 0 ? 'true' : 'false' }}"
                                    data-img="{{ asset('uploads/'.$product->pro_img1) }}"
                                >
                                    Add to Cart
                                </button>
                                
                                <button id="main-wishlist-btn" class="btn btn-outline-danger btn-lg {{ in_array($product->id, $wishlistProductIds ?? []) ? 'active btn-danger' : '' }}" 
                                    onclick="toggleWishlist(this)" data-product-id="{{ $product->id }}"
                                    style="{{ in_array($product->id, $wishlistProductIds ?? []) ? 'background-color: var(--primary-color); color: white;' : '' }}">
                                    <svg class="icon icon-wishlist" width="20" height="20" viewBox="0 0 26 22" fill="{{ in_array($product->id, $wishlistProductIds ?? []) ? 'currentColor' : 'none' }}" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z" fill="currentColor" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Large Description -->
                <div class="row mt-5">
                    <div class="col-12">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button" role="tab">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews ({{ $review_count }})</button>
                            </li>
                            @if($product->pro_size_chart)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="size-tab" data-bs-toggle="tab" data-bs-target="#size-chart" type="button" role="tab">Size Chart</button>
                            </li>
                            @endif
                        </ul>
                        <div class="tab-content p-4 border border-top-0 rounded-bottom" id="myTabContent">
                            <div class="tab-pane fade show active" id="desc" role="tabpanel">
                                <p>{!! nl2br(e($product->pro_desc)) !!}</p>
                                
                                <div class="mt-4">
                                    <p>{{ $product->brand->brand_name ?? 'Our Brand' }} brings you the {{ $product->pro_title }}, carefully crafted for people who value quality, comfort, and reliability. Designed to fit modern lifestyles, this product stands out in the {{ $product->category->proCat_name ?? 'Fashion' }} category for its thoughtful design and everyday usability.</p>
                                    
                                    <p>Whether you’re using it for daily needs or special occasions, the {{ $product->pro_title }} delivers consistent performance and long-lasting durability. {{ $product->brand->brand_name ?? 'Our Brand' }} focuses on maintaining high standards, ensuring every product meets customer expectations across Bangladesh.</p>
                                    
                                    <p>If you’re searching for a trusted {{ $product->category->proCat_name ?? 'Fashion' }} product that balances value and quality, the {{ $product->brand->brand_name ?? 'Our Brand' }} {{ $product->pro_title }} is a smart choice. Available online in Bangladesh with convenient ordering and reliable service.</p>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="reviews" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8">
                                        @forelse($reviews as $review)
                                            <div class="review-card">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h5 class="mb-1">{{ $review->user_name }}</h5>
                                                        <div class="star-rating" style="color: #fcc904;">
                                                            @for($i=1; $i<=5; $i++)
                                                                @if($i <= $review->rating) ★ @else ☆ @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <span class="text-muted small">{{ \Carbon\Carbon::parse($review->created_at)->format('d M, Y') }}</span>
                                                </div>
                                                <p class="mt-2 mb-0">{{ $review->comment }}</p>
                                            </div>
                                        @empty
                                            <p class="text-muted">No reviews yet.</p>
                                        @endforelse
                                    </div>
                                    <div class="col-md-4">
                                        @if(Auth::check())
                                            @if($user_has_purchased)
                                                @if(!$user_already_reviewed)
                                                    <div class="card bg-light border-0">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Write a Review</h5>
                                                            <form action="{{ route('user.reviews.store') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Rating</label>
                                                                    <select name="rating" class="form-select" required>
                                                                        <option value="5">5 ★★★★★</option>
                                                                        <option value="4">4 ★★★★☆</option>
                                                                        <option value="3">3 ★★★☆☆</option>
                                                                        <option value="2">2 ★★☆☆☆</option>
                                                                        <option value="1">1 ★☆☆☆☆</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Review</label>
                                                                    <textarea name="comment" class="form-control" rows="3" required></textarea>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary w-100">Submit Review</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="alert alert-success">
                                                        You have already reviewed this product. Thank you!
                                                    </div>
                                                @endif
                                            @else
                                                <div class="alert alert-warning">
                                                    You must purchase this product to leave a review.
                                                </div>
                                            @endif
                                        @else
                                            <div class="alert alert-info">
                                                Please <a href="{{ route('user_login') }}">login</a> to leave a review.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($product->pro_size_chart)
                            <div class="tab-pane fade" id="size-chart" role="tabpanel">
                                <img src="{{ asset('uploads/'. $product->pro_size_chart) }}" class="img-fluid rounded" alt="Size Chart">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                <div class="row mt-5">
                    <div class="col-12 mb-4">
                        <h2 class="section-heading primary-color text-center">Related Products</h2>
                    </div>
                    @foreach($related_products as $r_product)
                        @php
                            $adjusted = $r_product->adjusted_price;
                            $final = $r_product->final_price;
                            $droppercent = 0;
                            if($adjusted > 0 && $final < $adjusted) {
                                $droppercent = (($adjusted - $final)/$adjusted)*100;
                            }
                            $inWishlist = in_array($r_product->id, $wishlistProductIds ?? []);
                        @endphp
                        <div class="col-lg-3 col-md-6 col-6 mb-4">
                            <div class="minimalist-card" onclick="window.location='{{ route('product.show', $r_product->id) }}'">
                                <div class="position-relative product-img-wrapper">
                                    <img src="{{ asset('uploads/'. $r_product->pro_img1) }}" alt="{{ $r_product->pro_title }}">
                                    
                                    <div class="position-absolute top-0 end-0 m-3" onclick="event.stopPropagation()">
                                        <div class="wishlist-btn {{ $inWishlist ? 'active' : '' }}" onclick="toggleWishlist(this)" data-product-id="{{ $r_product->id }}">
                                            <svg class="icon icon-wishlist" width="20" height="20" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#333" stroke-width="2">
                                                <path d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z" />
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    @if($droppercent > 0)
                                    <div class="product-badge position-absolute top-0 start-0 m-3">
                                         <span class="badge rounded-pill" style="background-color: #000 !important; color: #fff;">{{ intval($droppercent) }}% OFF</span>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="card-body text-center mt-3 px-3">
                                    <h3 class="product-card-title mb-2" style="font-size: 16px; font-weight: 600;">
                                        <a href="{{ route('product.show', $r_product->id) }}" class="text-dark text-decoration-none">{{ $r_product->pro_title }}</a>
                                    </h3>
                                    <div class="product-card-price mb-3">
                                        <span class="fw-bold" style="color: var(--primary-color);">৳ {{ $r_product->final_price }}</span>
                                        @if($r_product->final_price < $r_product->adjusted_price)
                                        <span class="text-muted text-decoration-line-through small ms-2">৳ {{ $r_product->adjusted_price }}</span>
                                        @endif
                                    </div>
                                    <div class="shop-now-btn text-center pb-2">
                                        <a href="{{ route('product.show', $r_product->id) }}" class="btn btn-primary btn-sm rounded-pill px-4">Shop Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>

        @include('main_view.include.footer')
        @include('main_view.include.drawermenu')
        @include('main_view.include.drawercart')
        @include('main_view.include.script')
        @include('main_view.include.css')
        <script src="{{asset('main_view/assets/js/main.js')}}"></script>
        
        <script>
            function toggleWishlist(btn) {
                const productId = btn.getAttribute('data-product-id');
                
                // If it's the main detailed wishlist button, visual toggle might be different
                // For now, we assume this function handles both by class toggle
                
                fetch("{{ route('user.wishlist.toggle') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => {
                    if (response.status === 401) {
                        window.location.href = "{{ route('user_login') }}";
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data) {
                        if (data.status === 'added') {
                            btn.classList.add('active');
                            // If it's the main button (which might not use 'active' class same way), handle it:
                             if(btn.id === 'main-wishlist-btn') {
                                 btn.classList.remove('btn-outline-danger');
                                 btn.classList.add('btn-danger'); // Make it filled
                                 btn.querySelector('svg').style.fill = 'currentColor';
                             }
                        } else {
                            btn.classList.remove('active');
                             if(btn.id === 'main-wishlist-btn') {
                                 btn.classList.remove('btn-danger');
                                 btn.classList.add('btn-outline-danger');
                                 btn.querySelector('svg').style.fill = 'none';
                             }
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            function updateMainImage(element) {
                document.getElementById('main-product-image').src = element.src;
                document.querySelectorAll('.product-gallery-thumb').forEach(el => el.classList.remove('active'));
                element.classList.add('active');
            }

            function selectSize(element, size, stock) {
                document.getElementById('selected-size').value = size;
                document.getElementById('selected-stock').value = stock; // Update available stock
                
                document.querySelectorAll('.size-option').forEach(el => el.classList.remove('selected'));
                element.classList.add('selected');
                
                // Update Button Dataset mainly for reference, though we use hidden input mostly
                const btn = document.getElementById('single-page-add-to-cart');
                btn.dataset.stock = stock;

                // Reset Qty to 1 when size changes to avoid stale overflow
                document.getElementById('product-qty').value = 1;
                document.getElementById('qty-display').innerText = 1;

                // Hide error if selected
                document.getElementById('size-error').style.display = 'none';
                document.getElementById('qty-error').style.display = 'none';
            }
            
            function showNotification() {
                const box = document.getElementById('cart-notification');
                if(box) {
                    box.style.display = 'block';
                    setTimeout(() => { box.style.display = 'none'; }, 3000);
                }
            }
            
            function closeNotification() {
                const box = document.getElementById('cart-notification');
                if(box) box.style.display = 'none';
            }

            function updateQty(change) {
                const qtyDisplay = document.getElementById('qty-display');
                const qtyInput = document.getElementById('product-qty');
                const btn = document.getElementById('single-page-add-to-cart');
                
                // Determine effective stock
                let stock = parseInt(btn.dataset.stock || 0);
                const hasSizes = btn.dataset.hasSizes === 'true';
                
                if (hasSizes) {
                    // Logic: if sizes exist, check if size is selected
                    const selectedStockC = document.getElementById('selected-stock').value;
                     // If size not selected yet, stock is treated as 0 or we don't allow up
                    stock = parseInt(selectedStockC || 0);
                    
                    if(stock === 0 && !document.getElementById('selected-size').value) {
                       // Size not selected, maybe prompt user? For now just limit to 1 visually or 0
                       // We allow 1 but on Add we fail
                    }
                }

                const errorMsg = document.getElementById('qty-error');
                
                let newQty = parseInt(qtyInput.value || 1) + change;
                
                if (newQty > stock) {
                    errorMsg.style.display = 'block';
                    return;
                }
                
                if (newQty < 1) newQty = 1;
                
                // Hide error if valid
                errorMsg.style.display = 'none';
                
                // Update DOM
                qtyInput.value = newQty;
                qtyDisplay.innerText = newQty;
            }

            // Single Product Add to Cart Logic
            document.addEventListener('DOMContentLoaded', function() {
                const addToCartBtn = document.getElementById('single-page-add-to-cart');
                if(addToCartBtn) {
                    addToCartBtn.addEventListener('click', function() {
                        const hasSizes = this.dataset.hasSizes === 'true';
                        let size = null;
                        let maxStock = parseInt(this.dataset.stock); 

                        if (hasSizes) {
                             size = document.getElementById('selected-size').value;
                             if(!size) {
                                const err = document.getElementById('size-error');
                                err.style.display = 'block';
                                setTimeout(() => { err.style.display = 'none'; }, 3000);
                                return;
                             }
                             // Use stock from the selected size
                             maxStock = parseInt(document.getElementById('selected-stock').value);
                        } else {
                             // No sizes, use global stock
                             maxStock = parseInt(this.dataset.stock);
                        }
                        
                        // Proceed to Add to Cart
                        const btn = this;
                        const id = btn.dataset.id;
                        let cart = getCart(); // Global function from drawercart
                        
                        // Check global stock if qty > maxStock (redundant check)
                        const qtyToAdd = parseInt(document.getElementById('product-qty').value || 1);
                        if(qtyToAdd > maxStock) {
                            alert('Not enough stock available.');
                            return;
                        }

                        // We use the same ID for simplicity in this user request, 
                        // but ideally distinct ID for variants is better.
                        // Storing size in the object.
                        
                        // Generate a unique key for the cart item
                        const cartKey = hasSizes && size ? `${id}-${size}` : id;

                         if (!cart[cartKey]) {
                            cart[cartKey] = {
                                id: id,
                                title: btn.dataset.title,
                                price: parseFloat(btn.dataset.price),
                                qty: qtyToAdd,
                                stock: maxStock,
                                img: btn.dataset.img,
                                size: size // Save the selected size
                            };
                        } else {
                            // Check stock before adding
                            let currentQtyInCart = cart[cartKey].qty;
                             
                            if (currentQtyInCart + qtyToAdd > maxStock) {
                                alert('Stock limit reached. You already have ' + currentQtyInCart + ' in cart.');
                                return;
                            }
                            
                            cart[cartKey].qty += qtyToAdd;
                            if(size) cart[cartKey].size = size; 
                        }

                        saveCart(cart); // Updates header and drawer
                        showNotification();
                        
                        // Open Drawer
                        let drawer = document.getElementById('drawer-cart');
                        if(drawer) new bootstrap.Offcanvas(drawer).show();
                    });
                }
            });
        </script>

        <!-- Cart Notification (Copied from index) -->
        <div id="cart-notification" style="display: none; position: fixed; top: 20px; right: 20px; background: #16a34a; color: white; padding: 12px 20px; border-radius: 6px; z-index: 9999; font-size: 16px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
            Great choice! Item added to cart
            <button onclick="closeNotification()" style="margin-left: 10px; background: none; border: none; color: white; font-size: 18px; cursor: pointer;">×</button>
        </div>
        
    </div>
</body>
</html>
