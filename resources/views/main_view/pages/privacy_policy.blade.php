<!doctype html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>Privacy Policy - {{ $portfolio->company_name ?? 'Pinkush' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Privacy Policy of {{ $portfolio->company_name ?? 'Pinkush' }}">
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
                        <h2 class="section-heading primary-color mb-4 text-center">Privacy Policy</h2>
                        <div class="content">
                            <p>Your privacy is important to us. It is {{ $portfolio->company_name ?? 'Pinkush' }}'s policy to respect your privacy regarding any information we may collect from you across our website.</p>
                            
                            <h4 class="mt-4">1. Information We Collect</h4>
                            <p>We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent.</p>

                            <h4 class="mt-4">2. How We Use Information</h4>
                            <p>We use the information we collect to operate and maintain our website, identifying you as a user of the website, and sending you administrative emails.</p>

                            <h4 class="mt-4">3. Security</h4>
                            <p>We value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it.</p>

                            <p class="mt-4"><i>Note: This is a sample Privacy Policy page. Please update with your actual policy.</i></p>
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
