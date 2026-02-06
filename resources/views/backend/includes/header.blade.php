@php
        $portfolio = App\Models\Portfolio::first();
    @endphp
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $portfolio ? Str::limit($portfolio->about, 160) : 'Admin Panel' }}">
    <meta name="keywords" content="Admin, Dashboard, E-commerce">
    <meta name="author" content="{{ $portfolio ? $portfolio->company_name : 'Admin' }}">
    <link rel="icon" href="{{ $portfolio && $portfolio->favicon ? asset($portfolio->favicon) : asset('backend/assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $portfolio && $portfolio->favicon ? asset($portfolio->favicon) : asset('backend/assets/images/favicon.png') }}" type="image/x-icon">
    <title>Admin - @yield('title') - {{ $portfolio ? $portfolio->company_name : config('app.name') }}</title>




    <meta name="csrf-token" content="{{ csrf_token() }}" />


    @include('backend.includes.css')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>


</head>
