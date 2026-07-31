<!doctype html>
<html lang="en" class="no-js">
<head>
    <title>{{ $portfolio->company_name ?? 'Pinkush' }} - Forgot Password</title>
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
</head>

<body>
    <div class="body-wrapper">
        @include('main_view.include.header')

        <div class="container d-flex justify-content-center align-items-center vh-100">
            <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%; border-radius: 12px;">
                <div class="text-center mb-4">
                    <h3 class="mt-2">Forgot Password</h3>
                    <p class="text-muted">Enter your email address to receive a password reset link.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success py-2 small mb-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger py-2 small mb-3">
                        @foreach($errors->all() as $err) {{ $err }} @endforeach
                    </div>
                @endif

                <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="emailInput" class="form-label">Email Address</label>
                        <input type="email" name="email" id="emailInput" class="form-control"
                               placeholder="Enter your email" value="{{ old('email') }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3" id="submitBtn">Send Reset Link</button>

                    <div class="text-center mt-3">
                        <a href="{{ route('user_login') }}">Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    document.getElementById('forgotPasswordForm').addEventListener('submit', function(){
        var btn = document.getElementById('submitBtn');
        if (btn) { btn.disabled = true; btn.textContent = 'Sending Link...'; }
    });
</script>
</html>
