<!doctype html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>FAQ - {{ $portfolio->company_name ?? 'Pinkush' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Frequently Asked Questions - {{ $portfolio->company_name ?? 'Pinkush' }}">
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
                        <h2 class="section-heading primary-color mb-4 text-center">Frequently Asked Questions</h2>
                        
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item mb-3 border bg-white rounded">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        How can I track my order?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        You can track your order status in the "My Orders" section of your account dashboard.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border bg-white rounded">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        What is the return policy?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        We accept returns within 7 days of delivery. Please check our <a href="{{ route('return.policy') }}">Return Policy</a> page for more details.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border bg-white rounded">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Do you ship internationally?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Currently, we are shipping within Bangladesh. We plan to expand internationally soon.
                                    </div>
                                </div>
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
