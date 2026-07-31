<!doctype html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>Order Success - {{ $portfolio->company_name ?? 'Pinkush' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Order Placed Successfully">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ isset($portfolio->favicon) ? asset($portfolio->favicon) : asset('main_view/assets/img/favicon.png')}}" type="image/x-icon">
    @include('main_view.include.css')
    
    <style>
        .success-section {
            background-color: #f8f9fa;
            min-height: 60vh;
            display: flex;
            align-items: center;
        }
        .success-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 3rem;
            border: 1px solid #eef0f2;
            text-align: center;
        }
        .success-icon {
            font-size: 4rem;
            color: #198754;
            margin-bottom: 1.5rem;
            display: inline-block;
            animation: scaleUp 0.5s ease-out;
        }
        .order-id-box {
            background: #f1fbf4;
            border: 2px dashed #a3cfbb;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 2rem 0;
            transition: all 0.3s;
        }
        .order-id-box:hover {
            background: #e6f7ec;
            border-color: #75b798;
        }
        .btn-continue {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: #fff !important;
            font-weight: 600;
            padding: 0.75rem 2.5rem;
            border-radius: 30px;
            transition: all 0.3s;
        }
        .btn-continue:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        @keyframes scaleUp {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>

<body>
    <div class="body-wrapper">
        @include('main_view.include.header')

        <main id="MainContent" class="content-for-layout">
            <div class="success-section py-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-8 col-sm-10">
                            <div class="success-card">
                                <div class="success-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                </div>
                                
                                <h1 class="display-6 fw-bold mb-3" style="color: var(--heading-color);">Order Placed Successfully!</h1>
                                <p class="text-muted fs-5">Thank you for your purchase. We have received your order and are processing it.</p>
                                
                                <div class="order-id-box">
                                    <h4 class="mb-2 text-dark">Your Order ID</h4>
                                    <span class="fs-3 fw-bold text-success font-monospace d-block mb-3" id="order-id-text">{{ $orderNumber }}</span>
                                    
                                    <div class="alert alert-warning mb-0 text-start" role="alert" style="font-size: 0.9rem; line-height: 1.5; border-color: #ffe69c; background-color: #fff3cd;">
                                        <i class="fa fa-exclamation-triangle me-2 text-warning-emphasis"></i>
                                        <strong>ATTENTION:</strong> Since you placed this order as a guest, please **copy or write down this Order ID** now. You will need it to track your order status or request support.
                                    </div>
                                </div>
                                
                                <a href="{{ route('home') }}" class="btn btn-continue btn-lg">
                                    Continue Shopping
                                </a>
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
