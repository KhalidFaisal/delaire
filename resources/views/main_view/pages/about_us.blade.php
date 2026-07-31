<!doctype html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>About Us - {{ $portfolio->company_name ?? 'Pinkush' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="About {{ $portfolio->company_name ?? 'Pinkush' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ isset($portfolio->favicon) ? asset($portfolio->favicon) : asset('main_view/assets/img/favicon.png')}}" type="image/x-icon">
    @include('main_view.include.css')
</head>

<body>
    <div class="body-wrapper">
        @include('main_view.include.header')

        <main id="MainContent" class="content-for-layout">
            <div class="container py-5">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                         <img src="{{ isset($portfolio->logo) ? asset($portfolio->logo) : asset('main_view/assets/img/logo.png') }}" alt="{{ $portfolio->company_name ?? 'Pinkush' }}" class="lazy-image img-fluid rounded shadow-sm" loading="lazy" >
                    </div>
                    <div class="col-lg-6">
                        <h2 class="section-heading primary-color mb-4">About {{ $portfolio->company_name ?? 'Pinkush' }}</h2>
                        <div class="content">
                            <p class="lead">{{ $portfolio->about ?? 'Welcome to our store. We are dedicated to providing the best products and service to our customers.' }}</p>
                            
                            <p>Founded with a passion for quality and design, {{ $portfolio->company_name ?? 'Pinkush' }} has been serving customers with a wide range of products. Our mission is to make your shopping experience enjoyable and hassle-free.</p>

                            <p>We carefully select our products to ensure they meet our high standards of quality and style.</p>
                            
                            <div class="mt-4">
                                <a href="{{ route('contact') }}" class="btn btn-primary">Contact Us</a>
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
    </div>
</body>
</html>
