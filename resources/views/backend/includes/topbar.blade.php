<!-- Page Header Start-->
<div class="page-main-header">
    <div class="main-header-right row m-0">
        <div class="main-header-left">
@php
                $portfolio = App\Models\Portfolio::first();
                $companyName = $portfolio ? $portfolio->company_name : config('app.name');
            @endphp
            <div class="logo-wrapper"><a href="{{ route('admin.dashboard') }}">
                    <h4>{{ $companyName }}</h4>
                </a></div>
            <div class="dark-logo-wrapper "><a href="{{ route('admin.dashboard') }}">
                    <h4>{{ $companyName }}</h4>
                </a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center"
                    id="sidebar-toggle"></i></div>
        </div>
        <div class="left-menu-header col">
            <ul>
                <li>

                </li>
            </ul>
        </div>
        <div class="nav-right col pull-right right-menu p-0">
            <ul class="nav-menus">
                <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i
                            data-feather="maximize"></i></a></li>
                <li>
                    <div class="mode"><i class="fa fa-moon-o"></i></div>
                </li>
                <li class="onhover-dropdown">
                    <div class="notification-box">
                        <i data-feather="bell"></i><span class="badge rounded-pill badge-secondary" id="notification-count"></span>
                    </div>
                    <ul class="notification-dropdown onhover-show-div">
                        <li>
                            <i data-feather="bell"></i>
                            <h6 class="f-18 mb-0">Notifications</h6>
                        </li>
                        <div id="notification-items" style="max-height: 300px; overflow-y: auto;">
                            <!-- Items injected via JS -->
                        </div>
                        <li class="p-0 text-center border-top-0">
                            <a class="btn btn-primary btn-sm btn-block" href="{{ route('admin.notifications.all') }}">View All</a>
                        </li>
                    </ul>
                </li>
                <li class="onhover-dropdown">
                    <div class="bookmark-box">
                        <img class="lazy-image img-30 rounded-circle" src="{{ asset('backend/assets/images/user/user.png') }}"
                            alt="Admin" loading="lazy" >
                    </div>
                    <div class="bookmark-dropdown onhover-show-div">
                        <div class="sidebar-user text-center"><a class="setting-primary"
                                href="{{ route('manage.admin') }}"><i data-feather="settings"></i></a><img
                                class="lazy-image img-90 rounded-circle" src="{{ asset('backend/assets/images/user/user.png') }}"
                                alt="Admin" loading="lazy" >
                            <div class="badge-bottom">
                            </div>
                            <a href="#">
                                @php
                                    $admin = Auth::guard('admin')->user();
                                @endphp
                                <h6 class="mt-3 f-14 f-w-600">{{ $admin ? $admin->name : 'Administrator' }}</h6>
                            </a>
                            <p class="mb-0 font-roboto">Admin</p>
                        </div>
                    </div>
                </li>
                <li class="onhover-dropdown p-0">
                    <button class="btn btn-primary-light" type="button">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"><i
                                    data-feather="log-out"></i> Logout</a>
                        </form>
                    </button>
                </li>
            </ul>
        </div>
        <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
    </div>
</div>
<!-- Page Header Ends -->
