<!doctype html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>{{ $portfolio->company_name ?? 'Pinkush' }}</title>
    <!-- meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $portfolio->about ?? 'Welcome to ' . ($portfolio->company_name ?? 'Pinkush') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ isset($portfolio->favicon) ? asset($portfolio->favicon) : asset('main_view/assets/img/favicon.png')}}" type="image/x-icon">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- all css -->
    @include('main_view.include.css')
    <style>
        /* Slideshow Text Color Override if needed */
        .slide-heading, .slide-content { color: var(--primary-color) !important; }
        .testimonial-icon-quote svg path { fill: var(--primary-color); }
        .wishlist-btn.active svg { fill: var(--primary-color); stroke: var(--primary-color); }
        .product-card-price .fw-bold { color: var(--primary-color) !important; }
        
        #cart-notification {
            transform: translateY(-20px);
            transition: all 0.3s ease;
        }
    </style>
</head>

<body>
    
    <div class="body-wrapper">
        
        @include('main_view.include.header')
        

        <main id="MainContent" class="content-for-layout">
            <!-- slideshow start -->
            @php 
                $contentSetting = \App\Models\ContentSetting::firstOrCreate([]); 
                $content = \App\Models\Content::first();
            @endphp
            @if($contentSetting->slider_active)
            <div class="slideshow-section position-relative">
                <div class="slideshow-active activate-slider" data-slick='{
                    "slidesToShow": 1, 
                    "slidesToScroll": 1, 
                    "dots": true,
                    "arrows": true,
                    "responsive": [
                        {
                        "breakpoint": 768,
                        "settings": {
                            "arrows": false
                        }
                        }
                    ]
                }'>

                   @if($content)
                    <div class="slide-item slide-item-bag position-relative">
                        <img class="lazy-image slide-img d-none d-md-block" src="{{ asset('uploads/'. $content->pro_image) }}" alt="slide-1" loading="lazy" >
                        <img class="lazy-image slide-img d-md-none" src="{{ asset('uploads/'. $content->pro_image) }}" alt="slide-1" loading="lazy" >
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex align-items-center justify-content-end">
                                <div class="content-box slide-content  py-4 text-center" Style="color:var(--primary-color)">
                                    <h2 class="slide-heading heading_72 animate__animated animate__fadeInUp "
                                       Style="color:var(--primary-color)" data-animation="animate__animated animate__fadeInUp">
                                        {{ $content->greetings }}
                                    </h2>
                                    
                                    <a class="btn-primary slide-btn animate__animated animate__fadeInUp"
                                        href="{{ route('search.product') }}"
                                        data-animation="animate__animated animate__fadeInUp">SHOP
                                        NOW</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide-item slide-item-bag position-relative">
                        <img class="lazy-image slide-img d-none d-md-block" src="{{ asset('uploads/'. $content->about_image) }}" alt="slide-2" loading="lazy" >
                        <img class="lazy-image slide-img d-md-none" src="{{ asset('uploads/'.$content->about_image) }}" alt="slide-2" loading="lazy" >
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex align-items-center justify-content-end">
                                <div class="content-box slide-content  py-4 text-center">
                                    <h2 Style="color:var(--primary-color)" class="slide-heading heading_72 animate__animated animate__fadeInUp"
                                        data-animation="animate__animated animate__fadeInUp">
                                        {{ $content->intro }}
                                    </h2>
                                    <p class="slide-subheading heading_24 animate__animated animate__fadeInUp"
                                        data-animation="animate__animated animate__fadeInUp">
                                       
                                    </p>
                                    <a class="btn-primary slide-btn animate__animated animate__fadeInUp"
                                        href="{{ route('search.product') }}"
                                        data-animation="animate__animated animate__fadeInUp">SHOP
                                        NOW</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide-item slide-item-bag position-relative">
                        <img class="lazy-image slide-img d-none d-md-block" src="{{ asset('uploads/'. $content->about_intro) }}" alt="slide-3" loading="lazy" >
                        <img class="lazy-image slide-img d-md-none" src="{{ asset('uploads/'. $content->about_intro) }}" alt="slide-3" loading="lazy" >
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex align-items-center justify-content-center">
                                <div class="content-box slide-content  py-4 text-center">
                                    <h2 Style="color:var(--primary-color)" class="slide-heading heading_72 animate__animated animate__fadeInUp"
                                        data-animation="animate__animated animate__fadeInUp">
                                        {{ $content->slider3_header }}
                                    </h2>
                                    
                                    <a class="btn-primary slide-btn animate__animated animate__fadeInUp"
                                        href="{{ route('search.product') }}"
                                        data-animation="animate__animated animate__fadeInUp">SHOP
                                        NOW</a>
                                </div>
                            </div>
                        </div>
                    </div>
                   @else
                    <div class="slide-item slide-item-bag position-relative d-flex align-items-center justify-content-center" style="min-height: 400px; background: #f8f9fa;">
                         <div class="text-center">
                             <h2 class="slide-heading heading_72" style="color:var(--primary-color)">Welcome!</h2>
                             <p class="mt-3">Please configure slider content in Admin Panel to populate this area.</p>
                             <a class="btn-primary slide-btn mt-3" href="{{ route('search.product') }}">SHOP NOW</a>
                         </div>
                    </div>
                   @endif
                </div>
                <div class="activate-arrows"></div>
                <div class="activate-dots dot-tools"></div>
            </div>
            @endif
            <!-- slideshow end -->

            <!-- trusted badge start 
            <div class="trusted-section mt-100 overflow-hidden">
                <div class="trusted-section-inner">
                    <div class="container">
                        <div class="row justify-content-center trusted-row">
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="trusted-badge rounded p-0">
                                    <div class="trusted-icon">
                                        <img class="lazy-image icon-trusted" src="{{asset('main_view/assets/img/trusted/1.png')}}" alt="icon-1" loading="lazy" >
                                    </div>
                                    <div class="trusted-content">
                                        <h2 class="heading_18 trusted-heading">Free Shipping & Return</h2>
                                        <p class="text_16 trusted-subheading trusted-subheading-2">On all order over
                                            $99.00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="trusted-badge rounded p-0">
                                    <div class="trusted-icon">
                                        <img class="lazy-image icon-trusted" src="{{asset('main_view/assets/img/trusted/2.png')}}" alt="icon-2" loading="lazy" >
                                    </div>
                                    <div class="trusted-content">
                                        <h2 class="heading_18 trusted-heading">Customer Support 24/7</h2>
                                        <p class="text_16 trusted-subheading trusted-subheading-2">Instant access to
                                            support</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="trusted-badge rounded p-0">
                                    <div class="trusted-icon">
                                        <img class="lazy-image icon-trusted" src="{{asset('main_view/assets/img/trusted/3.png')}}" alt="icon-3" loading="lazy" >
                                    </div>
                                    <div class="trusted-content">
                                        <h2 class="heading_18 trusted-heading">100% Secure Payment</h2>
                                        <p class="text_16 trusted-subheading trusted-subheading-2">We ensure secure
                                            payment!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            trusted badge end -->

            <!-- Feature Category start -->
            @if($contentSetting->feature_content_active)
            <div class="grid-banner mt-100 overflow-hidden">
                <div class="collection-tab-inner mt-0">
                    <div class="container">
                        <div class="grid-container-2">
                            @if(isset($featureCategories) && $featureCategories->count() > 0)
                                @foreach($featureCategories as $key => $category)
                                    @php
                                        $gridItemClass = 'grid-item-' . ($key + 1);
                                        $aos = ($key == 2) ? 'fade-left' : 'fade-right'; // 3rd item fade-left
                                        $containerClass = ($key == 1) ? 'justify-content-end' : ''; // 2nd item right aligned
                                        $textClass = ($key == 1) ? 'text-end' : ''; // 2nd item text align right
                                    @endphp
                                    <a class="grid-item {{ $gridItemClass }} position-relative rounded mt-0 d-flex"  
                                       href="{{ route('search.product', ['pro_sub_category' => $category->subcategory_id]) }}"
                                       data-aos="{{ $aos }}" data-aos-duration="700">
                                        <img class="lazy-image banner-img rounded" src="{{ asset('uploads/' . $category->banner_image) }}" alt="banner-{{ $key + 1 }}" loading="lazy" >
                                        <div class="content-absolute content-slide">
                                            <div class="container height-inherit d-flex {{ $containerClass }}">
                                                <div class="content-box banner-content p-4 {{ $textClass }}">
                                                    <h2 class="heading_34 primary-color">{{ $category->subcategory->proSubCat_name ?? 'Category' }}</h2>
                                                    <!-- <p class="text_14 mt-2 primary-color">Get the best Product</p> -->
                                                    <span class="text_12 mt-4 link-underline d-block primary-color">
                                                        VIEW MORE
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <!-- Fallback if no data -->
                                <div class="col-12 text-center">
                                    <p>Please configure Feature Categories in Admin Panel</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!-- Feature Categoty end -->

            <!-- Notification -->

            <!-- collection start -->
            <div class="featured-collection mt-100 overflow-hidden">
                <div class="collection-tab-inner">
                    <div class="container">
                        <div class="section-header text-center">
                            <h2 class="section-heading primary-color">Featured Products</h2>
                        </div>
                        <div class="row">
                            @php
                            $products = App\Models\Product::orderBy('created_at', 'asc')->take(4)->get();
                                $count = 0;
                                $droppercent=0;
                            @endphp

                            <!-- Table rows with data -->
                            <!-- Minimalist Product Card CSS -->
                            <style>
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
                            </style>

                            <script>
                                function toggleWishlist(btn) {
                                    const productId = btn.getAttribute('data-product-id');
                                    
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
                                            } else {
                                                btn.classList.remove('active');
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                                }
                            </script>

                            @forelse($products as $product)
                                @php
                                    $count++;
                                    $adjusted = $product->adjusted_price;
                                    $final = $product->final_price;
                                    $droppercent = 0;
                                    if($adjusted > 0 && $final < $adjusted) {
                                        $droppercent = (($adjusted - $final)/$adjusted)*100;
                                    }
                                    $inWishlist = in_array($product->id, $wishlistProductIds ?? []);
                                @endphp
                            
                                <div class="col-lg-3 col-md-6 col-6 mb-4" data-aos="fade-up" data-aos-duration="700">
                                    <div class="minimalist-card" onclick="window.location='{{ route('product.show', $product->id) }}'">
                                        <div class="position-relative product-img-wrapper">
                                            <img src="{{ asset('uploads/'. $product->pro_img1) }}" alt="{{ $product->pro_title }}" loading="lazy"  class="lazy-image" >
                                            
                                            <div class="position-absolute top-0 end-0 m-3" onclick="event.stopPropagation()">
                                                <div class="wishlist-btn {{ $inWishlist ? 'active' : '' }}" onclick="toggleWishlist(this)" data-product-id="{{ $product->id }}">
                                                    <svg class="icon icon-wishlist" width="20" height="20" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#333" stroke-width="2">
                                                        <path d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            
                                            <div class="product-badge position-absolute top-0 start-0 m-3">
                                                 <span class="badge rounded-pill" style="background-color: #000 !important; color: #fff;">{{ intval($droppercent) }}% OFF</span>
                                            </div>
                                        </div>
                                        
                                        <div class="card-body text-center mt-3 px-3">
                                            <h3 class="product-card-title mb-2" style="font-size: 16px; font-weight: 600;">
                                                <a href="{{ route('product.show', $product->id) }}" class="text-dark text-decoration-none">{{ $product->pro_title }}</a>
                                            </h3>
                                            <div class="product-card-price mb-3">
                                                <span class="fw-bold" style="color: var(--primary-color);">৳ {{ $product->final_price }}</span>
                                                @if($product->final_price < $product->adjusted_price)
                                                <span class="text-muted text-decoration-line-through small ms-2">৳ {{ $product->adjusted_price }}</span>
                                                @endif
                                            </div>
                                            <div class="shop-now-btn text-center pb-2">
                                                <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary btn-sm rounded-pill px-4">Shop Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <h4 class="text-muted">No Featured Products Found</h4>
                                    <p class="text-muted">Please add products to your store.</p>
                                </div>
                            @endforelse
                            
                        </div>
                        <div class="view-all text-center" data-aos="fade-up" data-aos-duration="700">
                            <a class="btn-primary" href="{{ route('search.product') }}">VIEW ALL</a>
                        </div>
                    </div>
                </div>
            </div>
          

           

            <!-- single banner start -->
            @if($content && $content->banner_image && $contentSetting->banner_active)
            <div class="single-banner-section mt-100 overflow-hidden">
                <div class="position-relative overlay">
                    <img class="lazy-image single-banner-img" src="{{asset('uploads/'. $content->banner_image)}}" alt="slide-1" loading="lazy" >

                    <div class="content-absolute content-slide">
                        <div class="container height-inherit d-flex  ">
                            <div class="content-box single-banner-content py-4 " data-aos="fade-up"
                                data-aos-duration="700">
                                <h2 class="single-banner-heading heading_42 text-white animate__animated animate__fadeInUp"
                                    data-animation="animate__animated animate__fadeInUp" data-aos="fade-up"
                                    data-aos-duration="700">
                                    {{ $content->banner_header }}
                                </h2>
                                <p class="single-banner-text text_16 text-white animate__animated animate__fadeInUp"
                                    data-animation="animate__animated animate__fadeInUp" data-aos="fade-up"
                                    data-aos-duration="700">
                                    
                                </p>
                                <a class="btn-primary single-banner-btn animate__animated animate__fadeInUp"
                                    href="{{ route('search.product') }}"
                                    data-animation="animate__animated animate__fadeInUp" data-aos="fade-up"
                                    data-aos-duration="700">
                                    DISCOVER NOW
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!-- single banner end -->

             <!-- testimonial start -->
            @if($contentSetting->testimonial_active)
            <div class="testimonial-section mt-100 overflow-hidden home-section">
                <div class="testimonial-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5 col-md-12 col-12" data-aos="fade-right" data-aos-duration="700">
                                <div class="section-header">
                                    <h2 class="section-heading primary-color">What customer say</h2>
                                    <p class="section-subheading">
                                        Real stories. Real experiences. Real satisfaction.
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6 offset-lg-1 col-md-12 col-12" data-aos="fade-left"
                                data-aos-duration="700">
                                <div class="testimonial-container position-relative">
                                    <div class="testimonial-slideshow common-slider" data-slick='{
                                            "slidesToShow": 1, 
                                            "slidesToScroll": 1,
                                            "dots": false,
                                            "arrows": true
                                        }'>
                                        @if(isset($testimonials) && $testimonials->count() > 0)
                                            @foreach($testimonials as $testimonial)
                                                <div class="testimonial-item">
                                                    <div class="testimonial-icon-wrap d-flex align-items-center">
                                                        <div class="testimonial-icon-quote">
                                                            <svg width="40" height="29" viewBox="0 0 40 29" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M0 28.99L11.7 0H19.5L12.22 28.99H0ZM20.28 28.99L32.11 0H39.91L32.5 28.99H20.28Z"
                                                                    fill="#de2e79" />
                                                            </svg>
                                                        </div>
                                                        <div class="testimonial-icon-star d-flex align-items-center ms-3">
                                                            @for($i = 0; $i < $testimonial->rating; $i++)
                                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="star" loading="lazy"  class="lazy-image" >
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <p class="testimonial-review my-4 text_16">
                                                        “ {{ $testimonial->message }} ”
                                                    </p>
                                                    <div class="testimonial-reviewer d-flex align-items-center">
                                                        <div class="reviewer-img">
                                                            @if($testimonial->image)
                                                                <img src="{{ asset($testimonial->image) }}" alt="{{ $testimonial->name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%;" loading="lazy"  class="lazy-image" >
                                                            @else
                                                                <!-- Use a placeholder or keep existing logic if needed, here assuming a default or simple fallback -->
                                                                <div style="width: 70px; height: 70px; background: #eee; border-radius: 50%;"></div>
                                                            @endif
                                                        </div>
                                                        <div class="reviewer-info ms-4">
                                                            <h4 class="reviewer-name heading_18 mb-2 primary-color">{{ $testimonial->name }}</h4>
                                                            <p class="reviewer-desig text_14 m-0">{{ $testimonial->designation }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                             <div class="testimonial-item text-center">
                                                <p>No testimonials available.</p>
                                             </div>
                                        @endif
                                    </div>
                                    <div class="activate-arrows show-arrows-always article-arrows arrows-white"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!-- testimonial end -->
        </main>

        @include('main_view.include.footer')

        <!-- scrollup start -->
        <button id="scrollup">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>  
        </button>
        <!-- scrollup end -->

        <!-- drawer menu start -->
        @include('main_view.include.drawermenu')

        <!-- drawer cart start -->
        @include('main_view.include.drawercart')
        <!-- drawer cart end -->

        <!-- product quickview start -->
        @include('main_view.include.proquickview')
        <!-- product quickview end -->

        <!-- newsletter subscribe modal start -->
        

     <!-- all js -->
     @include('main_view.include.script')
        @include('main_view.include.css')
        <script src="{{asset('main_view/assets/js/main.js')}}"></script>
        <script src="{{asset('main_view/assets/js/vendor.js')}}"></script>


<script>
    <script>
function showNotification() {
    const box = document.getElementById('cart-notification');
    box.style.display = 'block';

    // Automatically hide after 3 seconds
    setTimeout(() => {
        box.style.display = 'none';
    }, 3000);
}

function closeNotification() {
    document.getElementById('cart-notification').style.display = 'none';
}

// Add this inside your add-to-cart click handler
document.addEventListener('click', function(e) {
    if (!e.target.classList.contains('add-to-cart') && !e.target.closest('.add-to-cart')) return;

    // Call the notification
    showNotification();
});
</script>
</script>

        
</body>


</html>

<script>



</script>

