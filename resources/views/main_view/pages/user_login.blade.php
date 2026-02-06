<!doctype html>
<html lang="en" class="no-js">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <title>{{ $portfolio->company_name ?? 'Pinkush' }} - Login</title>
    <!-- meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="meta description">
    <link rel="shortcut icon" href="{{ isset($portfolio->favicon) ? asset($portfolio->favicon) : asset('main_view/assets/img/favicon.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- all css -->
    @include('main_view.include.css')
    <style>
        #cart-notification {
    transform: translateY(-20px);
    transition: all 0.3s ease;
}


    </style>
</head>

<body>
    
    <div class="body-wrapper">
        
        @include('main_view.include.header')
        


<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%; border-radius: 12px;">
        <div class="text-center mb-4">
            <h3 class="mt-2">Welcome Back!</h3>
            <p class="text-muted">Login to continue shopping your favorite shoes</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2 small">
                @foreach($errors->all() as $err) {{ $err }} @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form id="loginForm" method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="mb-3">
                <label for="loginInput" class="form-label">Email</label>
                <input type="email" name="email" id="loginInput" class="form-control"
                       placeholder="Enter your email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label for="passwordInput" class="form-label">Password</label>
                <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>

            <div class="text-center mb-3 text-muted">or</div>

            <!-- Google Login -->
           <button type="button" class="btn btn-google w-100 d-flex align-items-center justify-content-center">
                <i class="fab fa-google me-2"></i>
                <span>Login with Google</span>
            </button>

            <div class="text-center mt-3">
                <a href="{{route('user_register')}}">Don't have an account? Sign Up</a>
            </div>
        </form>
    </div>
</div>
</div>
</body>
<script>
    document.getElementById('loginForm').addEventListener('submit', function(){
        var btn = this.querySelector('button[type="submit"]');
        if (btn) { btn.disabled = true; btn.textContent = 'Logging in...'; }
    });

    // Google Login button (wire to your OAuth route when ready)
    var googleBtn = document.querySelector('button.btn-google');
    if (googleBtn) {
        googleBtn.addEventListener('click', function() {
            window.location.href = '/auth/google';
        });
    }
</script>
