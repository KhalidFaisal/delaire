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
            
            <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('dashboard')}}"><i data-feather="home"></i><span>Dashboard</span></a>
          
            </li>
          <!-- Product Area Start-->
            <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                     <i class="fa fa-laptop"></i>
                      <span style="margin-left: 5px;">Product Management </span>
                  </a>

                  <ul class="nav-submenu menu-content">
                  <li><a href="{{route('manage.procat')}}"><i class="fa fa-list-ol"></i> Manage Category</a></li>
                  <li><a href="{{route('manage.proSubCat')}}"><i class="fa fa-plus-square"></i> Manage Sub Category</a></li>
                  <li><a href="{{route('manage.brand')}}"><i class="fa fa-tag"></i> Manage Brands</a></li>

                    <li><a href="{{route('all.product')}}"><i class="fa fa-laptop"></i>Manage products</a></li>
                    <li><a href="{{route('manage.reviews')}}"><i class="fa fa-star"></i>Manage Reviews</a></li>
                    <li><a href="{{route('admin.damage.stock')}}"><i class="fa fa-trash"></i> Damage Stock</a></li>
                  </ul>

            </li>
             <!-- Order Area Start-->
            <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                      <i class="fa fa-shopping-bag"></i>
                      <span style="margin-left: 5px;">Manage Orders</span>
                  </a>
                  <ul class="nav-submenu menu-content">
                    <li><a href="{{route('admin.orders.create')}}"><i class="fa fa-plus"></i> Create Order</a></li>
                    <li><a href="{{route('admin.orders.index')}}"><i class="fa fa-list"></i> All Orders</a></li>
                    <li><a href="{{route('admin.returns.index')}}"><i class="fa fa-undo"></i> Return Requests</a></li>
                  </ul>
            </li>
             <!-- Order Area End-->
             <!-- Blog Area Start-->
           <!-- Blog Area Start-->
            <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                      <i class="fa fa-newspaper-o"></i>
                      <span style="margin-left: 5px;">Manage Blog</span>
                  </a>

                  <ul class="nav-submenu menu-content">
                  <li><a href="{{route('create.category')}}"><i class="fa fa-plus"></i> Add Category</a></li>
                    <li><a href="{{route('create.blog')}}"><i class="fa fa-pencil"></i> Write Blog</a></li>
                    <li><a href="{{route('all.blog')}}"><i class="fa fa-list"></i> All Blog's</a></li>
                  </ul>

            </li>
             <!-- Blog Area Start-->
             
           <!-- Profile Area Start-->
            <!-- <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                      <i class="fa fa-user"></i>
                      <span style="margin-left: 5px;">Manage Profile</span>
                  </a>

                  <ul class="nav-submenu menu-content">
                    <li><a href="{{route('edit.profile')}}"><i class="fa fa-edit"></i>Edit profile</a></li>
                 
                  </ul> 

            </li> -->
             <!-- Profile Area Start-->

             <!-- Banner Area Start-->
            <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                      <i class="fa fa-cog"></i>
                      <span style="margin-left: 5px;">Manage Website</span>
                  </a>

                  <ul class="nav-submenu menu-content">
                    <li><a href="{{route('manage.portfolio')}}"><i class="fa fa-briefcase"></i> Manage Portfolio</a></li>
                    <li><a href="{{route('manage.content')}}"><i class="fa fa-file-text-o"></i> Manage Content</a></li>
                    <li><a href="{{route('manage.feature.category')}}"><i class="fa fa-th-large"></i> Feature Category</a></li>
                    <li><a href="{{route('manage.testimonial')}}"><i class="fa fa-comments"></i> Manage Testimonial</a></li>
                  </ul>

            </li>
            
            <li class="dropdown">
                  <a class="nav-link menu-title" href="javascript:void(0)">
                      <i class="fa fa-sliders"></i>
                      <span style="margin-left: 5px;">Manage Settings</span>
                  </a>
                  <ul class="nav-submenu menu-content">
                    <li><a href="{{route('manage.offers')}}"><i class="fa fa-percent"></i> Manage Offers</a></li>
                    <li><a href="{{route('manage.charges')}}"><i class="fa fa-truck"></i> Manage Charges</a></li>
                    <li><a href="{{route('manage.promocodes')}}"><i class="fa fa-ticket"></i> Manage Promo Codes</a></li>
                  </ul>
            </li>
             <!-- banner Area Start-->
            

           
            
          </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </div>
    </nav>
</header>
<!-- Page Sidebar Ends-->
