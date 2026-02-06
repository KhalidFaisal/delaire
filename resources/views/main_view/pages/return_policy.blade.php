<!doctype html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>Return Policy - {{ $portfolio->company_name ?? 'Pinkush' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Return Policy of {{ $portfolio->company_name ?? 'Pinkush' }}">
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
                        <h2 class="section-heading primary-color mb-4 text-center">Return Policy</h2>
                        <div class="content">
                            <p>Here at {{ $portfolio->company_name ?? 'Pinkush' }}, we want you to be completely satisfied with your purchase. If you are not satisfied with your purchase, you may return it within a specific period from the purchase date.</p>
                            
                            <h4 class="mt-4">1. Eligibility for Returns</h4>
                            <p>To be eligible for a return, your item must be unused and in the same condition that you received it. It must also be in the original packaging.</p>

                            <h4 class="mt-4">2. Non-returnable Items</h4>
                            <p>Several types of goods are exempt from being returned. Perishable goods such as food, flowers, newspapers or magazines cannot be returned.</p>

                            <h4 class="mt-4">3. Refund Process</h4>
                            <p>Once your return is received and inspected, we will send you an email to notify you that we have received your returned item. We will also notify you of the approval or rejection of your refund.</p>

                            <p class="mt-4"><i>Note: This is a sample return policy. Please update with your actual policy details from the admin panel or manually edit this file.</i></p>
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
