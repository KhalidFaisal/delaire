<!-- announcement bar start 
<div class="announcement-bar bg-1 py-1 py-lg-2">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-3 d-lg-block d-none">
                        <div class="announcement-call-wrapper">
                            <div class="announcement-call">
                                <a class="announcement-text text-white" href="tel:{{ $portfolio->contact_number ?? '+88 01712429662' }}">Call: {{ $portfolio->contact_number ?? '+88 01712429662' }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="announcement-text-wrapper d-flex align-items-center justify-content-center">
                            <p class="announcement-text text-white">New year sale - 30% off</p>
                        </div>
                    </div>
                    <div class="col-lg-3 d-lg-block d-none">
                        <div class="announcement-meta-wrapper d-flex align-items-center justify-content-end">
                            <div class="announcement-meta d-flex align-items-center">
                                <a class="announcement-login announcement-text text-white" href="{{ route('user_login') }}">
                                    <svg class="icon icon-user" width="10" height="11" viewBox="0 0 10 11" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5 0C3.07227 0 1.5 1.57227 1.5 3.5C1.5 4.70508 2.11523 5.77539 3.04688 6.40625C1.26367 7.17188 0 8.94141 0 11H1C1 8.78516 2.78516 7 5 7C7.21484 7 9 8.78516 9 11H10C10 8.94141 8.73633 7.17188 6.95312 6.40625C7.88477 5.77539 8.5 4.70508 8.5 3.5C8.5 1.57227 6.92773 0 5 0ZM5 1C6.38672 1 7.5 2.11328 7.5 3.5C7.5 4.88672 6.38672 6 5 6C3.61328 6 2.5 4.88672 2.5 3.5C2.5 2.11328 3.61328 1 5 1Z"
                                            fill="#fff" />
                                    </svg>
                                    <span>Login</span>
                                </a>
                                <span class="separator-login d-flex px-3">
                                    <svg width="2" height="9" viewBox="0 0 2 9" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4" d="M1 0.5V8.5" stroke="#FEFEFE" stroke-linecap="round" />
                                    </svg>
                                </span>
                                <div class="currency-wrapper">
                                    <button type="button" class="currency-btn btn-reset text-white"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <img class="flag" src="{{asset('main_view/assets/img/flag/usd.jpg')}}" alt="img">
                                        <span>USD</span>
                                        <span>
                                            <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff"
                                                stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </span>
                                    </button>

                                    <ul class="currency-list dropdown-menu dropdown-menu-end px-2">
                                        <li class="currency-list-item ">
                                            <a class="currency-list-option" href="#" data-value="USD">
                                                <img class="flag" src="{{asset('main_view/assets/img/flag/BD.jpg')}}" alt="img">
                                                <span>BDT</span>
                                            </a>
                                        </li>
                                        
                                       
                                    
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        announcement bar end -->


        <!-- header start -->
        <header class="sticky-header border-btm-black header-1">
            <div class="header-bottom">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-4 col-4">
                            <div class="header-logo">
                                <a href="{{route('home')}}" class="logo-main">
                                  <img src="{{ isset($portfolio->logo) ? asset($portfolio->logo) : asset('main_view/assets/img/logo.png') }}" style="width:50%;margin:2px;"loading="lazy" alt="{{ $portfolio->company_name ?? 'Pinkush' }}"> 
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-6 d-lg-block d-none">
                            <nav class="site-navigation">
                                <ul class="main-menu list-unstyled justify-content-center">
                                    <li class="menu-list-item nav-item has-dropdown active">
                                        <div class="mega-menu-header">
                                            <a class="nav-link" href="{{route('home')}}">
                                                Home
                                            </a>
                                            
                                        </div>
                                     </li>
                                    
                                     @php
                                        $cats = App\Models\Procategory::orderBy('created_at', 'asc')->get();
                                        
                                            $count=0;
                                        @endphp
                                            <!-- Table rows with data -->
                                            @foreach( $cats as $category)
                                            @php
                                        
                                            $count++;
                                            @endphp
                                     <li class="menu-list-item nav-item has-megamenu">
                                        <div class="mega-menu-header">
                                        <a class="nav-link" id="pro_category" name="pro_category" value="{{ $category->id }}" href="{{ route('search.product', ['pro_category' => $category->id]) }}">
                                                    {{ $category->proCat_name }}
                                                </a>
                                                <span class="open-submenu">
                                                <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="submenu-transform submenu-transform-desktop">
                                            <div class="container">
                                                <ul class="submenu megamenu-container list-unstyled">
                                                    <li class="menu-list-item nav-item-sub">
                                                        <div class="mega-menu-header">
                                                            <a class="nav-link-sub nav-text-sub megamenu-heading"
                                                                href="collection-left-sidebar.html">
                                                                Sub Category
                                                            </a>
                                                        </div>
                                                        <div class="submenu-transform megamenu-transform">
                                                            <ul class="megamenu list-unstyled">
                                                            @php
                                                            $subcats = App\Models\Prosubcategory::where('main_Cat' , $category->id)->get();
                                                            
                                                                $count=0;
                                                            @endphp
                                                                <!-- Table rows with data -->
                                                                @foreach( $subcats as $subcategory)
                                                                @php
                                                            
                                                                $count++;
                                                                @endphp


                                                                 <li class="menu-list-item nav-item-sub">
                                                                    <a class="nav-link-sub nav-text-sub"
                                                                        href="{{ route('search.product', ['pro_category' => $category->id, 'pro_sub_category' => $subcategory->id]) }}">
                                                                        {{ $subcategory->proSubCat_name }}
                                                                    
                                                                    </a>
                                                                </li>
                                                                @endforeach
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    <li class="menu-list-item nav-item-sub">
                                                        <div
                                                            class="mega-menu-header d-flex align-items-center justify-content-between">
                                                            <a class="nav-link-sub nav-text-sub megamenu-heading"
                                                                href="collection-right-sidebar.html">
                                                                Brands
                                                            </a>
                                                        </div>
                                                        <div class="submenu-transform megamenu-transform">
                                                            <ul class="megamenu list-unstyled">
                                                               
                                                            @php
                                                            // Correct logic: Find brands that actually have products in this category
                                                            $brandIds = App\Models\Product::where('main_category', $category->id)
                                                                            ->whereNotNull('pro_brand')
                                                                            ->distinct()
                                                                            ->pluck('pro_brand');
                                                            
                                                            $brand = App\Models\Probrand::whereIn('id', $brandIds)->get();
                                                            
                                                                $count=0;
                                                            @endphp
                                                                <!-- Table rows with data -->
                                                                @foreach( $brand as $brands)
                                                                @php
                                                            
                                                                $count++;
                                                                @endphp
                                                             
                                                            
                                                               <li class="menu-list-item nav-item-sub">
                                                                    <a class="nav-link-sub nav-text-sub"
                                                                        href="{{ route('search.product', ['pro_category' => $category->id, 'pro_brand' => $brands->id]) }}">{{ $brands->brand_name }}</a>
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    
                                                    <li class="menu-list-item nav-item-sub">
                                                        <div
                                                            class="mega-menu-header d-flex align-items-center justify-content-between">
                                                            @php
                                                                $randomFeature = App\Models\FeatureCategory::inRandomOrder()->first();
                                                                $randomImage = $randomFeature ? asset('uploads/' . $randomFeature->banner_image) : asset('main_view/assets/img/menu/1.jpg');
                                                            @endphp
                                                            <a class="mega-menu-img nav-link-sub nav-text-sub"
                                                                href="{{ $randomFeature ? route('search.product', ['pro_sub_category' => $randomFeature->subcategory_id]) : '#' }}">
                                                                <img class="menu-img" src="{{ $randomImage }}" alt="img">
                                                                <h2 class="img-menu-heading text_16 mt-2">Featured
                                                                    Collection</h2>
                                                                <div
                                                                    class="img-menu-action text_12 bg-transparent p-0">
                                                                    <span>DISCOVER NOW</span>
                                                                    <span>
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="30" height="18" fill="#000"
                                                                            class="icon-right-long" viewBox="0 0 16 16">
                                                                            <path fill-rule="evenodd"
                                                                                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                                                        </svg>
                                                                    </span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                    
                                  
                                    <li class="menu-list-item nav-item">
                                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-3 col-md-8 col-8">
                            <div class="header-action d-flex align-items-center justify-content-end">
                                <a class="header-action-item header-search" href="javascript:void(0)">
                                    <svg class="icon icon-search" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.75 0.250183C11.8838 0.250183 15.25 3.61639 15.25 7.75018C15.25 9.54608 14.6201 11.1926 13.5625 12.4846L19.5391 18.4611L18.4609 19.5392L12.4844 13.5627C11.1924 14.6203 9.5459 15.2502 7.75 15.2502C3.61621 15.2502 0.25 11.884 0.25 7.75018C0.25 3.61639 3.61621 0.250183 7.75 0.250183ZM7.75 1.75018C4.42773 1.75018 1.75 4.42792 1.75 7.75018C1.75 11.0724 4.42773 13.7502 7.75 13.7502C11.0723 13.7502 13.75 11.0724 13.75 7.75018C13.75 4.42792 11.0723 1.75018 7.75 1.75018Z"
                                            fill="black" />
                                    </svg>
                                </a>
                                <a class="header-action-item header-wishlist ms-4 d-none d-lg-block"
                                    href="{{ route('user.wishlist') }}">
                                    <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z"
                                            fill="black" />
                                    </svg>
                                </a>
                           <a class="header-action-item header-cart ms-4 position-relative" href="#drawer-cart" data-bs-toggle="offcanvas">
                                <svg class="icon icon-cart" width="24" height="26" viewBox="0 0 24 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z" fill="black"/>
                                </svg>

                                <!-- Cart count badge -->
                                <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    0
                                </span>
                                
                            </a>
                           <div class="header-profile-wrapper ms-4 d-none d-lg-block">
    <!-- PROFILE ICON -->
<a href="javascript:void(0)" class="header-profile-toggle">
    @auth
        @if(auth()->user()->avatar)
            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" class="header-profile-avatar" width="32" height="32">
        @else
            <svg class="icon icon-profile" width="26" height="26" viewBox="0 0 24 24"
                 fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="8" r="4"/>
                <path d="M4 20c0-4 4-8 8-8s8 4 8 8"/>
            </svg>
        @endif
    @else
        <svg class="icon icon-profile" width="26" height="26" viewBox="0 0 24 24"
             fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
             xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="8" r="4"/>
            <path d="M4 20c0-4 4-8 8-8s8 4 8 8"/>
        </svg>
    @endauth
</a>

<!-- DROPDOWN -->
<div class="profile-dropdown">
    @guest
    <div class="p-2 text-center">
        <p class="mb-2 text-muted small">Welcome to {{ $portfolio->company_name ?? 'Pinkush' }}</p>
        <a href="{{ route('user_login') }}" class="btn btn-dark btn-sm w-100">Login / Register</a>
    </div>
    @endguest

    @auth
    <div class="d-flex align-items-center mb-3 p-2 border-bottom">
        @if(auth()->user()->avatar)
            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" class="user-img me-2">
        @else
            <div class="user-img-placeholder me-2">
                {{ strtoupper(substr(auth()->user()->name ?: 'U', 0, 1)) }}
            </div>
        @endif
        <div class="overflow-hidden">
            <h6 class="m-0 text-truncate" style="font-size:14px;">{{ auth()->user()->name ?: 'User' }}</h6>
            <span class="text-muted small" style="font-size:11px;">Signed in</span>
        </div>
    </div>
    
    <div class="d-flex gap-2 p-2 pt-0">
        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-dark btn-sm flex-grow-1 d-flex align-items-center justify-content-center gap-2">
            <span>Dashboard</span>
        </a>
        
        <form action="{{ route('user.logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm h-100 d-flex align-items-center justify-content-center" title="Logout">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </button>
        </form>
    </div>
    @endauth
</div>

<script>
(function(){
    const wrapper = document.querySelector('.header-profile-wrapper');
    const dropdown = document.querySelector('.profile-dropdown');
    
    if (!wrapper || !dropdown) return;

    wrapper.addEventListener('click', function(e) {
        e.stopPropagation();
        const isVisible = dropdown.style.display === 'block';
        dropdown.style.display = isVisible ? 'none' : 'block';
    });

    document.addEventListener('click', function(e) { 
        if (!dropdown.contains(e.target) && !wrapper.contains(e.target)) {
            dropdown.style.display = 'none'; 
        }
    });
})();
</script>


<style>
.header-profile-wrapper {
    position: relative;
    cursor: pointer;
}

.header-profile-toggle img,
.header-profile-toggle svg {
    transition: transform 0.2s;
}

.header-profile-wrapper:hover .header-profile-toggle img,
.header-profile-wrapper:hover .header-profile-toggle svg {
    transform: scale(1.05);
}

.profile-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    margin-top: 10px;
    background: #fff;
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 8px;
    width: 240px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    z-index: 1000;
    padding: 6px;
    animation: fadeIn 0.2s ease-out;
}

.user-img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

.user-img-placeholder {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #f3f4f6;
    color: #374151;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

.header-profile-avatar {
    border-radius: 50%;
    object-fit: cover;
}
</style>
</div>

<a class="header-action-item header-hamburger ms-4 d-lg-none" href="#drawer-menu"
                                    data-bs-toggle="offcanvas">
                                    <svg class="icon icon-hamburger" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="3" y1="12" x2="21" y2="12"></line>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <line x1="3" y1="18" x2="21" y2="18"></line>
                                    </svg>
                                </a>
                        </div>
                    </div>
                </div>
                <div class="search-wrapper">
                    <div class="container">
                        <form action="{{ route('search.product') }}" method="GET" class="search-form d-flex align-items-center position-relative">
                            <button type="submit" class="search-submit bg-transparent pl-0 text-start">
                                <svg class="icon icon-search" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7.75 0.250183C11.8838 0.250183 15.25 3.61639 15.25 7.75018C15.25 9.54608 14.6201 11.1926 13.5625 12.4846L19.5391 18.4611L18.4609 19.5392L12.4844 13.5627C11.1924 14.6203 9.5459 15.2502 7.75 15.2502C3.61621 15.2502 0.25 11.884 0.25 7.75018C0.25 3.61639 3.61621 0.250183 7.75 0.250183ZM7.75 1.75018C4.42773 1.75018 1.75 4.42792 1.75 7.75018C1.75 11.0724 4.42773 13.7502 7.75 13.7502C11.0723 13.7502 13.75 11.0724 13.75 7.75018C13.75 4.42792 11.0723 1.75018 7.75 1.75018Z"
                                        fill="black" />
                                </svg>
                            </button>
                            <div class="search-input mr-4 w-100">
                                <input type="text" id="searchInput" name="query" placeholder="Search your products..." autocomplete="off">
                                <div id="searchResults" class="search-results"></div>
                            </div>
                            <div class="search-close">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon icon-close">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </div>
                        </form>
                    </div>
                </div>

<style>
.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: #fff;
    border: 1px solid #ddd;
    z-index: 9999;
    max-height: 400px;
    overflow-y: auto;
    display: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-radius: 0 0 8px 8px;
}
.search-result-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    cursor: pointer;
    transition: background 0.2s;
    text-decoration: none;
    color: inherit;
}
.search-result-item:hover {
    background: #f9f9f9;
}
.search-result-item img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    margin-right: 15px;
    border-radius: 4px;
}
.search-result-info h6 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}
.search-result-info span {
    font-size: 12px;
    color: #888;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    let timeoutId;

    if(searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const query = this.value;

            if (query.length < 2) {
                searchResults.style.display = 'none';
                searchResults.innerHTML = '';
                return;
            }

            timeoutId = setTimeout(function() {
                fetch(`{{ route('product.search.ajax') }}?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(product => {
                                const item = document.createElement('a');
                                item.href = product.url;
                                item.className = 'search-result-item';
                                item.innerHTML = `
                                    <img src="${product.image}" alt="${product.title}">
                                    <div class="search-result-info">
                                        <h6>${product.title}</h6>
                                        <span>$${product.price}</span>
                                    </div>
                                `;
                                searchResults.appendChild(item);
                            });
                            searchResults.style.display = 'block';
                        } else {
                            searchResults.innerHTML = '<div class="p-2 text-center text-muted">No results found</div>';
                            searchResults.style.display = 'block';
                        }
                    })
                    .catch(error => console.error('Error fetching search results:', error));
            }, 300);
        });

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }
});
</script>
            </div>
            <!-- Mobile Menu Drawer -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="drawer-menu">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title fw-bold">Menu</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <nav class="mobile-nav">
                        <ul class="list-unstyled m-0">
                            <!-- User Auth Links (Mobile) -->
                            <li class="mobile-nav-item bg-light p-3">
                                @auth
                                    <div class="d-flex align-items-center mb-2">
                                        @if(auth()->user()->avatar)
                                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="rounded-circle me-2" width="40" height="40" alt="Avatar">
                                        @else
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="m-0">{{ auth()->user()->name }}</h6>
                                            <small class="text-muted">Logged in</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('user.dashboard') }}" class="btn btn-primary w-100 btn-sm mb-2">Dashboard</a>
                                    <form action="{{ route('user.logout') }}" method="POST">
                                        @csrf
                                        <button class="btn btn-outline-danger w-100 btn-sm">Logout</button>
                                    </form>
                                @else
                                    <h6 class="mb-2">Welcome!</h6>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('user_login') }}" class="btn btn-outline-primary w-100 btn-sm">Login</a>
                                        <a href="{{ route('user_register') }}" class="btn btn-primary w-100 btn-sm">Register</a>
                                    </div>
                                @endauth
                            </li>

                            <!-- Main Links -->
                            <li class="mobile-nav-item border-bottom">
                                <a href="{{ route('home') }}" class="mobile-nav-link p-3 d-block text-dark text-decoration-none">Home</a>
                            </li>

                            <!-- Categories Accordion -->
                            <li class="mobile-nav-item border-bottom">
                                <div class="accordion accordion-flush" id="mobileCatAccordion">
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed p-3 shadow-none bg-transparent fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseCats">
                                                Categories
                                            </button>
                                        </h2>
                                        <div id="flush-collapseCats" class="accordion-collapse collapse" data-bs-parent="#mobileCatAccordion">
                                            <div class="accordion-body p-0">
                                                <ul class="list-unstyled m-0">
                                                    @foreach(App\Models\Procategory::orderBy('created_at', 'asc')->get() as $cat)
                                                    <li class="border-bottom bg-light">
                                                        @php
                                                            $subcats = App\Models\Prosubcategory::where('main_Cat', $cat->id)->get();
                                                            $hasSub = $subcats->count() > 0;
                                                        @endphp
                                                        
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            @if($hasSub)
                                                                <a href="#subcat-{{ $cat->id }}" data-bs-toggle="collapse" class="d-block p-3 text-dark text-decoration-none flex-grow-1">
                                                                    {{ $cat->proCat_name }}
                                                                </a>
                                                                <a class="p-3 text-dark" data-bs-toggle="collapse" href="#subcat-{{ $cat->id }}" role="button">
                                                                    <i class="fas fa-chevron-down small"></i>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('search.product', ['pro_category' => $cat->id]) }}" class="d-block p-3 text-dark text-decoration-none flex-grow-1">
                                                                    {{ $cat->proCat_name }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                        
                                                        @if($hasSub)
                                                            <div class="collapse" id="subcat-{{ $cat->id }}">
                                                                <ul class="list-unstyled ps-4 bg-white">
                                                                    <li class="border-bottom">
                                                                        <a href="{{ route('search.product', ['pro_category' => $cat->id]) }}" class="d-block p-2 text-primary text-decoration-none small fw-bold">
                                                                            View All {{ $cat->proCat_name }}
                                                                        </a>
                                                                    </li>
                                                                    @foreach($subcats as $sub)
                                                                        <li class="border-bottom">
                                                                            <a href="{{ route('search.product', ['pro_category' => $cat->id, 'pro_sub_category' => $sub->id]) }}" class="d-block p-2 text-muted text-decoration-none small">
                                                                                {{ $sub->proSubCat_name }}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="mobile-nav-item border-bottom">
                                <a href="{{ route('contact') }}" class="mobile-nav-link p-3 d-block text-dark text-decoration-none">Contact Us</a>
                            </li>
                             <li class="mobile-nav-item border-bottom">
                                <a href="{{ route('about') }}" class="mobile-nav-link p-3 d-block text-dark text-decoration-none">About Us</a>
                            </li>
                             <li class="mobile-nav-item border-bottom">
                                <a href="{{ route('faq') }}" class="mobile-nav-link p-3 d-block text-dark text-decoration-none">FAQ</a>
                            </li>
                            <li class="mobile-nav-item border-bottom">
                                <a href="{{ route('user.wishlist') }}" class="mobile-nav-link p-3 d-block text-dark text-decoration-none">
                                    <i class="far fa-heart me-2"></i> Wishlist
                                </a>
                            </li>
                             <li class="mobile-nav-item border-bottom">
                                <a href="tel:{{ $portfolio->contact_number ?? '+88 01712429662' }}" class="mobile-nav-link p-3 d-block text-dark text-decoration-none">
                                    <i class="fas fa-phone-alt me-2"></i> {{ $portfolio->contact_number ?? '+88 01712429662' }}
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <!-- header end -->
      