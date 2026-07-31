<!-- Page Sidebar Start-->
<header class="main-nav">
    
    <nav>
      <div class="main-navbar">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="mainnav">
          <ul class="nav-menu custom-scrollbar">
            <li class="back-btn">
              <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
            
            @if(auth('admin')->user()->hasPermission('dashboard'))
            <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('admin.dashboard')}}"><i data-feather="home"></i><span>Dashboard</span></a></li>
            @endif
            
            <!-- Stock Management Area Start-->
            @if(auth('admin')->user()->hasPermission('inventory') || auth('admin')->user()->hasPermission('current_stock') || auth('admin')->user()->hasPermission('damage_stock'))
            <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                      <i class="fa fa-shopping-cart"></i>
                      <span style="margin-left: 5px;">Manage Stock</span>
                  </a>
                  <ul class="nav-submenu menu-content">
                    @if(auth('admin')->user()->hasPermission('inventory'))
                    <li><a href="{{ route('admin.inventory.index') }}"><i class="fa fa-list-alt"></i> Inventory</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('current_stock'))
                    <li><a href="{{ route('admin.current.stock') }}"><i class="fa fa-cubes"></i> Current Stock Items</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('damage_stock'))
                    <li><a href="{{ route('admin.damage.stock') }}"><i class="fa fa-trash"></i> Damage Stock</a></li>
                    @endif
                  </ul>
            </li>
            @endif
            <!-- Stock Management Area End-->

            <!-- Product Area Start-->
            @if(auth('admin')->user()->hasPermission('pro_category') || auth('admin')->user()->hasPermission('pro_sub_category') || auth('admin')->user()->hasPermission('pro_brand') || auth('admin')->user()->hasPermission('products') || auth('admin')->user()->hasPermission('reviews'))
            <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                     <i class="fa fa-laptop"></i>
                      <span style="margin-left: 5px;">Product Management </span>
                  </a>
                  <ul class="nav-submenu menu-content">
                    @if(auth('admin')->user()->hasPermission('pro_category'))
                    <li><a href="{{route('manage.procat')}}"><i class="fa fa-list-ol"></i> Manage Category</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('pro_sub_category'))
                    <li><a href="{{route('manage.proSubCat')}}"><i class="fa fa-plus-square"></i> Manage Sub Category</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('pro_brand'))
                    <li><a href="{{route('manage.brand')}}"><i class="fa fa-tag"></i> Manage Brands</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('products'))
                    <li><a href="{{route('all.product')}}"><i class="fa fa-laptop"></i>Manage products</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('reviews'))
                    <li><a href="{{route('manage.reviews')}}"><i class="fa fa-star"></i>Manage Reviews</a></li>
                    @endif
                  </ul>
            </li>
            @endif
            
            <!-- Order Area Start-->
            @if(auth('admin')->user()->hasPermission('create_order') || auth('admin')->user()->hasPermission('all_orders') || auth('admin')->user()->hasPermission('return_requests'))
            <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                      <i class="fa fa-shopping-bag"></i>
                      <span style="margin-left: 5px;">Manage Orders</span>
                  </a>
                  <ul class="nav-submenu menu-content">
                    @if(auth('admin')->user()->hasPermission('create_order'))
                    <li><a href="{{route('admin.orders.create')}}"><i class="fa fa-plus"></i> Create Order</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('all_orders'))
                    <li><a href="{{route('admin.orders.index')}}"><i class="fa fa-list"></i> All Orders</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('return_requests'))
                    <li><a href="{{route('admin.returns.index')}}"><i class="fa fa-undo"></i> Return Requests</a></li>
                    @endif
                  </ul>
            </li>
            @endif
            <!-- Order Area End-->
             
            <!-- Blog Area Start-->
            @if(auth('admin')->user()->hasPermission('blog_category') || auth('admin')->user()->hasPermission('write_blog') || auth('admin')->user()->hasPermission('all_blogs'))
            <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                      <i class="fa fa-newspaper-o"></i>
                      <span style="margin-left: 5px;">Manage Blog</span>
                  </a>
                  <ul class="nav-submenu menu-content">
                    @if(auth('admin')->user()->hasPermission('blog_category'))
                    <li><a href="{{route('create.category')}}"><i class="fa fa-plus"></i> Add Category</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('write_blog'))
                    <li><a href="{{route('create.blog')}}"><i class="fa fa-pencil"></i> Write Blog</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('all_blogs'))
                    <li><a href="{{route('all.blog')}}"><i class="fa fa-list"></i> All Blog's</a></li>
                    @endif
                  </ul>
            </li>
            @endif
            <!-- Blog Area End-->

            <!-- Website Management Area Start-->
            @if(auth('admin')->user()->hasPermission('portfolio') || auth('admin')->user()->hasPermission('content') || auth('admin')->user()->hasPermission('feature_category') || auth('admin')->user()->hasPermission('content_setting') || auth('admin')->user()->hasPermission('testimonial'))
            <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                      <i class="fa fa-cog"></i>
                      <span style="margin-left: 5px;">Manage Website</span>
                  </a>
                  <ul class="nav-submenu menu-content">
                    @if(auth('admin')->user()->hasPermission('portfolio'))
                    <li><a href="{{route('manage.portfolio')}}"><i class="fa fa-briefcase"></i> Manage Portfolio</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('content'))
                    <li><a href="{{route('manage.content')}}"><i class="fa fa-file-text-o"></i> Manage Content</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('feature_category'))
                    <li><a href="{{route('manage.feature.category')}}"><i class="fa fa-th-large"></i> Feature Category</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('content_setting'))
                    <li><a href="{{route('manage.content.setting')}}"><i class="fa fa-toggle-on"></i> Content Setting</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('testimonial'))
                    <li><a href="{{route('manage.testimonial')}}"><i class="fa fa-comments"></i> Manage Testimonial</a></li>
                    @endif
                  </ul>
            </li>
            @endif
            
            <!-- Settings Area Start-->
            @if(auth('admin')->user()->hasPermission('offers') || auth('admin')->user()->hasPermission('charges') || auth('admin')->user()->hasPermission('promo_codes') || auth('admin')->user()->hasPermission('app_settings') || auth('admin')->user()->hasPermission('email_account') || auth('admin')->user()->hasPermission('login_settings') || auth('admin')->user()->hasPermission('manage_admin') || auth('admin')->user()->hasPermission('logs'))
            <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                      <i class="fa fa-sliders"></i>
                      <span style="margin-left: 5px;">Manage Settings</span>
                  </a>
                  <ul class="nav-submenu menu-content">
                    @if(auth('admin')->user()->hasPermission('offers'))
                    <li><a href="{{route('manage.offers')}}"><i class="fa fa-percent"></i> Manage Offers</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('charges'))
                    <li><a href="{{route('manage.charges')}}"><i class="fa fa-truck"></i> Manage Charges</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('promo_codes'))
                    <li><a href="{{route('manage.promocodes')}}"><i class="fa fa-ticket"></i> Manage Promo Codes</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('app_settings'))
                    <li><a href="{{route('manage.app.settings')}}"><i class="fa fa-cogs"></i> App Settings</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('email_account'))
                    <li><a href="{{route('manage.email.account')}}"><i class="fa fa-envelope"></i> Manage Email Account</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('login_settings'))
                    <li><a href="{{route('manage.login')}}"><i class="fa fa-sign-in"></i> Manage Login</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('manage_admin'))
                    <li><a href="{{route('admin.manage.index')}}"><i class="fa fa-user-plus"></i> Manage Admin</a></li>
                    @endif
                    @if(auth('admin')->user()->hasPermission('logs'))
                    <li><a href="{{route('admin.logs.index')}}"><i class="fa fa-history"></i> Logs</a></li>
                    @endif
                  </ul>
            </li>
            @endif
            
            <!-- Customer Management Area -->
            @if(auth('admin')->user()->hasPermission('customers'))
            <li class="dropdown">
                  <a class="nav-link menu-title link-nav" href="{{ route('admin.customers.index') }}">
                      <i class="fa fa-users"></i>
                      <span style="margin-left: 5px;">Manage Customers</span>
                  </a>
            </li>
            @endif
            
          </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </div>
    </nav>
</header>
<!-- Page Sidebar Ends-->
