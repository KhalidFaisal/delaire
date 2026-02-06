<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%; border-radius: 12px;">
        <div class="text-center mb-4">
            <img src="{{asset('main_view/assets/img/logo.png')}}" alt="Pinkush Logo" style="height:50px;">
            <h3 class="mt-2">Welcome Back!</h3>
            <p class="text-muted">Login to continue shopping your favorite shoes</p>
        </div>

        <!-- Login Form -->
        <form id="loginForm">
            <div class="mb-3">
                <label for="loginInput" class="form-label">Email or Phone</label>
                <input type="text" class="form-control" id="loginInput" placeholder="Enter email or phone" required>
            </div>

            <div class="mb-3">
                <label for="passwordInput" class="form-label">Password</label>
                <input type="password" class="form-control" id="passwordInput" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>

            <div class="text-center mb-3 text-muted">or</div>

            <!-- Google Login -->
            <button type="button" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center">
                <img src="{{asset('main_view/assets/img/google-icon.svg')}}" alt="Google" style="height:20px; margin-right:8px;">
                Login with Google
            </button>

            <div class="text-center mt-3">
                <a href="{{route('register')}}">Don't have an account? Sign Up</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e){
        e.preventDefault();

        const login = document.getElementById('loginInput').value.trim();
        const password = document.getElementById('passwordInput').value.trim();

        if(!login || !password){
            alert('Please enter both email/phone and password');
            return;
        }

        // Example: You can replace this with AJAX call to your backend
        console.log('Logging in with:', login, password);

        // Mock login success
        alert('Login successful! Redirecting...');
        window.location.href = '/dashboard'; // Change to your dashboard route
    });

    // Google Login button
    const googleBtn = document.querySelector('button.btn-outline-dark');
    googleBtn.addEventListener('click', () => {
        // Redirect to Google OAuth
        window.location.href = '/auth/google'; // Change to your Google OAuth route
    });
</script>
