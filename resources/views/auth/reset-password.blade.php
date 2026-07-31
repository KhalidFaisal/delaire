<!doctype html>
<html lang="en" class="no-js">
<head>
    <title>{{ $portfolio->company_name ?? 'Pinkush' }} - Reset Password</title>
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
            <div class="card shadow-sm p-4" style="max-width: 420px; width: 100%; border-radius: 12px;">
                <div class="text-center mb-4">
                    <h3 class="mt-2">Reset Password</h3>
                    <p class="text-muted">Enter your new password details below.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 small mb-3">
                        @foreach($errors->all() as $err) {{ $err }} @endforeach
                    </div>
                @endif

                <form id="resetPasswordForm" method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="mb-3">
                        <label for="emailInput" class="form-label">Email Address</label>
                        <input type="email" name="email" id="emailInput" class="form-control"
                               placeholder="Enter your email" value="{{ old('email', $request->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="passwordInput" class="form-label">New Password</label>
                        <input type="password" name="password" id="passwordInput" class="form-control"
                               placeholder="Minimum 8 characters" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirmPasswordInput" class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="confirmPasswordInput" class="form-control"
                               placeholder="Repeat new password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3" id="submitBtn">Reset & Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
document.getElementById('resetPasswordForm').addEventListener('submit', function(e){
    var password = document.getElementById('passwordInput').value;
    var confirmPassword = document.getElementById('confirmPasswordInput').value;
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
    var btn = document.getElementById('submitBtn');
    if (btn) { btn.disabled = true; btn.textContent = 'Resetting...'; }
});
</script>
</html>
