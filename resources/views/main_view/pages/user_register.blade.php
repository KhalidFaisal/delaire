<!doctype html>
<html lang="en">
<head>
    <title>{{ $portfolio->company_name ?? 'Pinkush' }} - Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{ isset($portfolio->favicon) ? asset($portfolio->favicon) : asset('main_view/assets/img/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @include('main_view.include.css')
</head>

<body>
<div class="body-wrapper">

    @include('main_view.include.header')

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm p-4" style="max-width: 420px; width:100%; border-radius:12px;">
            
            <div class="text-center mb-4">
                <h3>Create Account</h3>
                <p class="text-muted">Register to start shopping</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger py-2 small">
                    @foreach($errors->all() as $err) {{ $err }} @endforeach
                </div>
            @endif

            <form id="registerForm" method="POST" action="{{ route('register.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="identity" id="identity" class="form-control"
                           placeholder="your@email.com" value="{{ old('identity') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control"
                           placeholder="Minimum 8 characters" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Repeat Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100" id="registerBtn">
                    Register & Send OTP
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('user_login') }}">Already have an account? Login</a>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e){
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('password_confirmation').value;
    if(password.length < 8){
        e.preventDefault();
        alert('Password must be at least 8 characters');
        return;
    }
    if(password !== confirmPassword){
        e.preventDefault();
        alert('Passwords do not match');
        return;
    }
    document.getElementById('registerBtn').disabled = true;
    document.getElementById('registerBtn').textContent = 'Sending OTP...';
});
</script>

</body>
</html>
