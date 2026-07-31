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
    <style>
        .content-body {
            font-size: 15px;
            line-height: 1.8;
            color: #555555;
        }
        .content-body h1, .content-body h2, .content-body h3, .content-body h4 {
            color: {{ $portfolio->brand_color_1 ?? '#de2e79' }};
            margin-top: 25px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .content-body ul {
            padding-left: 0;
            margin-bottom: 20px;
            list-style-type: none;
        }
        .content-body ul li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 12px;
        }
        .content-body ul li::before {
            content: "•";
            color: {{ $portfolio->brand_color_1 ?? '#de2e79' }};
            font-weight: bold;
            font-size: 22px;
            position: absolute;
            left: 5px;
            top: -3px;
        }
        .content-body ol {
            padding-left: 20px;
            margin-bottom: 20px;
        }
        .content-body ol li {
            margin-bottom: 12px;
            padding-left: 5px;
        }
        .content-body p {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="body-wrapper">
        @include('main_view.include.header')

        <main id="MainContent" class="content-for-layout">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <h2 class="section-heading primary-color mb-4 text-center">Return Policy</h2>
                        <div class="content content-body">
                            @if(!empty($portfolio->return_policy))
                                @php
                                    $lines = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $portfolio->return_policy))));
                                @endphp
                                @if(count($lines) > 0)
                                    <ul>
                                        @foreach($lines as $line)
                                            <li>{{ $line }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted text-center">No return policy has been defined yet.</p>
                                @endif
                            @else
                                <p class="text-muted text-center">No return policy has been defined yet.</p>
                            @endif
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
