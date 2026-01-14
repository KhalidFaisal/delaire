<!doctype html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>Pinkush</title>
    <!-- meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="meta description">
    <link rel="shortcut icon" href="{{asset('main_view/assets/img/favicon.png')}}" type="image/x-icon">

    <!-- all css -->
    @include('main_view.include.css')
</head>

<body>
    <div class="body-wrapper">
        
        @include('main_view.include.header')
        

        <main id="MainContent" class="content-for-layout">
            <!-- slideshow start -->
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
                @php
                            $sliders = App\Models\Content::orderBy('created_at', 'asc')->get();
                            $count = 0;
                          
                        @endphp

                        <!-- Table rows with data -->
                        @foreach($sliders as $slider)
                            @php
                                $count++;
                            @endphp

                    <div class="slide-item slide-item-bag position-relative">
                        <img class="slide-img d-none d-md-block" src="{{ asset('uploads/'. $slider->pro_image) }}" alt="slide-1">
                        <img class="slide-img d-md-none" src="{{ asset('uploads/'. $slider->pro_image) }}" alt="slide-1">
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex align-items-center justify-content-end">
                                <div class="content-box slide-content  py-4 text-center" Style="color:#de2e79">
                                    <h2 class="slide-heading heading_72 animate__animated animate__fadeInUp "
                                       Style="color:#de2e79" data-animation="animate__animated animate__fadeInUp">
                                        {{ $slider->greetings }}
                                    </h2>
                                    
                                    <a class="btn-primary slide-btn animate__animated animate__fadeInUp"
                                        href="collection-left-sidebar.html"
                                        data-animation="animate__animated animate__fadeInUp">SHOP
                                        NOW</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide-item slide-item-bag position-relative">
                        <img class="slide-img d-none d-md-block" src="{{ asset('uploads/'. $slider->about_image) }}" alt="slide-2">
                        <img class="slide-img d-md-none" src="{{ asset('uploads/'.$slider->about_image) }}" alt="slide-2">
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex align-items-center justify-content-end">
                                <div class="content-box slide-content  py-4 text-center">
                                    <h2 Style="color:#de2e79" class="slide-heading heading_72 animate__animated animate__fadeInUp"
                                        data-animation="animate__animated animate__fadeInUp">
                                        Discover The Best Toner
                                    </h2>
                                    <p class="slide-subheading heading_24 animate__animated animate__fadeInUp"
                                        data-animation="animate__animated animate__fadeInUp">
                                       
                                    </p>
                                    <a class="btn-primary slide-btn animate__animated animate__fadeInUp"
                                        href="collection-left-sidebar.html"
                                        data-animation="animate__animated animate__fadeInUp">SHOP
                                        NOW</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide-item slide-item-bag position-relative">
                        <img class="slide-img d-none d-md-block" src="{{ asset('uploads/'. $slider->about_intro) }}" alt="slide-3">
                        <img class="slide-img d-md-none" src="{{ asset('uploads/'. $slider->about_intro) }}" alt="slide-3">
                        <div class="content-absolute content-slide">
                            <div class="container height-inherit d-flex align-items-center justify-content-center">
                                <div class="content-box slide-content  py-4 text-center">
                                    <h2 Style="color:#de2e79" class="slide-heading heading_72 animate__animated animate__fadeInUp"
                                        data-animation="animate__animated animate__fadeInUp">
                                        Discover The Best Furniture
                                    </h2>
                                    
                                    <a class="btn-primary slide-btn animate__animated animate__fadeInUp"
                                        href="collection-left-sidebar.html"
                                        data-animation="animate__animated animate__fadeInUp">SHOP
                                        NOW</a>
                                </div>
                            </div>
                        </div>
                    </div>@endforeach
                </div>
                <div class="activate-arrows"></div>
                <div class="activate-dots dot-tools"></div>
            </div>
            <!-- slideshow end -->

            <!-- trusted badge start 
            <div class="trusted-section mt-100 overflow-hidden">
                <div class="trusted-section-inner">
                    <div class="container">
                        <div class="row justify-content-center trusted-row">
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="trusted-badge rounded p-0">
                                    <div class="trusted-icon">
                                        <img class="icon-trusted" src="{{asset('main_view/assets/img/trusted/1.png')}}" alt="icon-1">
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
                                        <img class="icon-trusted" src="{{asset('main_view/assets/img/trusted/2.png')}}" alt="icon-2">
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
                                        <img class="icon-trusted" src="{{asset('main_view/assets/img/trusted/3.png')}}" alt="icon-3">
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

            <!-- banner start -->
            <div class="grid-banner mt-100 overflow-hidden">
                <div class="collection-tab-inner mt-0">
                    <div class="container">
                        <div class="grid-container-2">
                            <a class="grid-item grid-item-1 position-relative rounded mt-0 d-flex"  href="{{ route('search.product', ['pro_category' => 6]) }}"
                                data-aos="fade-right" data-aos-duration="700">
                                <img class="banner-img rounded" src="{{asset('main_view/assets/img/pro1.jpg')}}" alt="banner-1">
                                <div class="content-absolute content-slide">
                                    <div class="container height-inherit d-flex">
                                        <div class="content-box banner-content p-4">
                                            <h2 class="heading_34 primary-color">Men</h2>
                                            <!-- <p class="text_14 mt-2 primary-color">Get the best Product</p> -->
                                            <span class="text_12 mt-4 link-underline d-block primary-color">
                                                VIEW MORE
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a class="grid-item grid-item-2 position-relative rounded mt-0 d-flex" href="{{ route('search.product', ['pro_category' => 7]) }}"
                                data-aos="fade-right" data-aos-duration="700">
                                <img class="banner-img rounded" src="{{asset('main_view/assets/img/pro2.jpg')}}" alt="banner-1">
                                <div class="content-absolute content-slide">
                                    <div class="container height-inherit d-flex justify-content-end">
                                        <div class="content-box banner-content p-4 text-end">
                                            <h2 class="heading_34 primary-color">Women</h2>
                                            <!-- <p class="text_14 mt-2 primary-color">Get Instant Cashback</p> -->
                                            <span class="text_12 mt-4 link-underline d-block primary-color">
                                                VIEW MORE
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a class="grid-item grid-item-3 position-relative rounded mt-0 d-flex" href="{{ route('search.product', ['pro_category' => 5]) }}"
                                data-aos="fade-left" data-aos-duration="700">
                                <img class="banner-img rounded" src="{{asset('main_view/assets/img/pro3.jpg')}}" alt="banner-1">
                                <div class="content-absolute content-slide">
                                    <div class="container height-inherit d-flex">
                                        <div class="content-box banner-content p-4">
                                            <h2 class="heading_34 primary-color">Shoe</h2>
                                            <!-- <p class="text_14 mt-2 primary-color">Get Instant Cashback</p> -->
                                            <span class="text_12 mt-4 link-underline d-block primary-color">
                                                VIEW MORE
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- banner end -->

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
                            @foreach($products as $product)
                                @php
                                    $count++;
                                    $droppercent= (($product->pro_price -$product->pro_sprice)/$product->pro_price)*100; 
                                @endphp
                            
                           
            

                            <div class="col-lg-3 col-md-6 col-6" data-aos="fade-up" data-aos-duration="700">
                                <div class="product-card">
                                    <div class="product-card-img">
                                        <a class="hover-switch" href="collection-left-sidebar.html">
                                            <img class="secondary-img" src="{{ asset('uploads/'. $product->pro_img1) }}"
                                                alt="product-img">
                                            <img class="primary-img" src="{{ asset('uploads/'. $product->pro_img1) }}"
                                                alt="product-img">
                                        </a>

                                        <div class="product-badge">
                                            <span class="badge-label badge-percentage rounded">

                                            {{ intval($droppercent) }}%
                                            </span>
                                        </div>

                                        <div class="product-card-action product-card-action-2 justify-content-center">
                                            <a href="#quickview-modal" class="action-card action-quickview"
                                                data-bs-toggle="modal">
                                                <svg width="26" height="26" viewBox="0 0 26 26" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M10 0C15.5117 0 20 4.48828 20 10C20 12.3945 19.1602 14.5898 17.75 16.3125L25.7188 24.2812L24.2812 25.7188L16.3125 17.75C14.5898 19.1602 12.3945 20 10 20C4.48828 20 0 15.5117 0 10C0 4.48828 4.48828 0 10 0ZM10 2C5.57031 2 2 5.57031 2 10C2 14.4297 5.57031 18 10 18C14.4297 18 18 14.4297 18 10C18 5.57031 14.4297 2 10 2ZM11 6V9H14V11H11V14H9V11H6V9H9V6H11Z"
                                                        fill="#de2e79" />
                                                </svg>
                                            </a>

                                            <a href="#" class="action-card action-wishlist">
                                                <svg class="icon icon-wishlist" width="26" height="22"
                                                    viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z"
                                                        fill="#de2e79" />
                                                </svg>
                                            </a>

                                            <a href="javascript:void(0)"
                                                class="action-card action-addtocart add-to-cart-btn"
                                                data-id="{{ $product->id }}"
                                                data-title="{{ $product->pro_title }}"
                                                data-price="{{ $product->pro_sprice }}"
                                                data-img="{{ asset('uploads/'.$product->pro_img1) }}" class="action-card action-addtocart">
                                                <svg class="icon icon-cart" width="24" height="26" viewBox="0 0 24 26"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z"
                                                        fill="#de2e79" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="product-card-details">
                                       
                                        <h3 class="product-card-title">
                                            <a href="collection-left-sidebar.html">{{ $product->pro_title }}</a>
                                        </h3>
                                        <div class="product-card-price">
                                            <span class="card-price-regular"> ৳ {{ $product->pro_sprice }}</span>
                                            <span class="card-price-compare text-decoration-line-through">৳ {{ $product->pro_price }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @endforeach
                            
                        </div>
                        <div class="view-all text-center" data-aos="fade-up" data-aos-duration="700">
                            <a class="btn-primary" href="{{ route('search.product') }}">VIEW ALL</a>
                        </div>
                    </div>
                </div>
            </div>
          

           

            <!-- single banner start -->
            <div class="single-banner-section mt-100 overflow-hidden">
                <div class="position-relative overlay">
                    <img class="single-banner-img" src="{{asset('main_view/assets/img/videobanner.jpg')}}" alt="slide-1">

                    <div class="content-absolute content-slide">
                        <div class="container height-inherit d-flex  ">
                            <div class="content-box single-banner-content py-4 " data-aos="fade-up"
                                data-aos-duration="700">
                                <h2 class="single-banner-heading heading_42 text-white animate__animated animate__fadeInUp"
                                    data-animation="animate__animated animate__fadeInUp" data-aos="fade-up"
                                    data-aos-duration="700">
                                    Shoe, Shoe & Shoe
                                </h2>
                                <p class="single-banner-text text_16 text-white animate__animated animate__fadeInUp"
                                    data-animation="animate__animated animate__fadeInUp" data-aos="fade-up"
                                    data-aos-duration="700">
                                    Best Shoe Supplier in the Country
                                </p>
                                <a class="btn-primary single-banner-btn animate__animated animate__fadeInUp"
                                    href="collection-left-sidebar.html"
                                    data-animation="animate__animated animate__fadeInUp" data-aos="fade-up"
                                    data-aos-duration="700">
                                    DISCOVER NOW
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- single banner end -->

             <!-- testimonial start -->
            <div class="testimonial-section mt-100 overflow-hidden home-section">
                <div class="testimonial-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5 col-md-12 col-12" data-aos="fade-right" data-aos-duration="700">
                                <div class="section-header">
                                    <h2 class="section-heading primary-color">What customer say</h2>
                                    <p class="section-subheading">
                                        The services provided by the officials was smooth and satisfactory. Products and
                                        goods delivered were up to satisfaction.
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
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                </div>
                                            </div>
                                            <p class="testimonial-review my-4 text_16">
                                                “ I am purchasing furniture from Pinkush since the last 6 years. I love
                                                their
                                                prompt service and so far I have faced no complaints with their
                                                furniture.”
                                            </p>
                                            <div class="testimonial-reviewer d-flex align-items-center">
                                                <div class="reviewer-img">
                                                    <img src="{{asset('main_view/assets/img/testimonial/john.jpg')}}" alt="img">
                                                </div>
                                                <div class="reviewer-info ms-4">
                                                    <h4 class="reviewer-name heading_18 mb-2 primary-color">Floyd Miles
                                                    </h4>
                                                    <p class="reviewer-desig text_14 m-0">Executive, Hypebeast</p>
                                                </div>
                                            </div>
                                        </div>
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
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                </div>
                                            </div>
                                            <p class="testimonial-review my-4 text_16">
                                                “ I am purchasing furniture from Pinkush since the last 6 years. I love
                                                their
                                                prompt service and so far I have faced no complaints with their
                                                furniture.”
                                            </p>
                                            <div class="testimonial-reviewer d-flex align-items-center">
                                                <div class="reviewer-img">
                                                    <img src="{{asset('main_view/assets/img/testimonial/john.jpg')}}" alt="img">
                                                </div>
                                                <div class="reviewer-info ms-4">
                                                    <h4 class="reviewer-name heading_18 mb-2 primary-color">Floyd Miles
                                                    </h4>
                                                    <p class="reviewer-desig text_14 m-0">Executive, Hypebeast</p>
                                                </div>
                                            </div>
                                        </div>
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
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                    <img src="{{asset('main_view/assets/img/icon/star.png')}}" alt="img">
                                                </div>
                                            </div>
                                            <p class="testimonial-review my-4 text_16">
                                                “ I am purchasing furniture from Pinkush since the last 6 years. I love
                                                their
                                                prompt service and so far I have faced no complaints with their
                                                furniture.”
                                            </p>
                                            <div class="testimonial-reviewer d-flex align-items-center">
                                                <div class="reviewer-img">
                                                    <img src="{{asset('main_view/assets/img/testimonial/john.jpg')}}" alt="img">
                                                </div>
                                                <div class="reviewer-info ms-4">
                                                    <h4 class="reviewer-name heading_18 mb-2 primary-color">Floyd Miles
                                                    </h4>
                                                    <p class="reviewer-desig text_14 m-0">Executive, Hypebeast</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="activate-arrows show-arrows-always article-arrows arrows-white"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
</body>


</html>