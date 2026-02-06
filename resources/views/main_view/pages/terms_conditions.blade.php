<!doctype html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>Terms & Conditions - {{ $portfolio->company_name ?? 'Pinkush' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Terms and Conditions of {{ $portfolio->company_name ?? 'Pinkush' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ isset($portfolio->favicon) ? asset($portfolio->favicon) : asset('main_view/assets/img/favicon.png')}}" type="image/x-icon">
    @include('main_view.include.css')
</head>

<body>
    <div class="body-wrapper">
        @include('main_view.include.header')

        <main id="MainContent" class="content-for-layout">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <h2 class="section-heading primary-color mb-4 text-center">Terms & Conditions</h2>
                        <div class="content">
                            <p>Welcome to {{ $portfolio->company_name ?? 'Pinkush' }}!</p>
                            <p>These terms and conditions outline the rules and regulations for the use of {{ $portfolio->company_name ?? 'Pinkush' }}'s Website.</p>
                            
                            <h4 class="mt-4">1. Acceptance of Terms</h4>
                            <p>By accessing this website we assume you accept these terms and conditions. Do not continue to use {{ $portfolio->company_name ?? 'Pinkush' }} if you do not agree to take all of the terms and conditions stated on this page.</p>

                            <h4 class="mt-4">2. Cookies</h4>
                            <p>We employ the use of cookies. By accessing {{ $portfolio->company_name ?? 'Pinkush' }}, you agreed to use cookies in agreement with the {{ $portfolio->company_name ?? 'Pinkush' }}'s Privacy Policy.</p>

                            <h4 class="mt-4">3. License</h4>
                            <p>Unless otherwise stated, {{ $portfolio->company_name ?? 'Pinkush' }} and/or its licensors own the intellectual property rights for all material on {{ $portfolio->company_name ?? 'Pinkush' }}. All intellectual property rights are reserved.</p>

                            <p class="mt-4"><i>Note: This is a sample Terms & Conditions page. Please update with your actual terms.</i></p>
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
