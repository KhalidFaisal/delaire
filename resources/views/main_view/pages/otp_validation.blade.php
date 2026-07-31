<!doctype html>
<html lang="en">
<head>
    <title>{{ $portfolio->company_name ?? 'Pinkush' }} - Verify OTP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{ isset($portfolio->favicon) ? asset($portfolio->favicon) : asset('main_view/assets/img/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @include('main_view.include.css')
    <style>
        .otp-box {
            width: 48px;
            height: 52px;
            text-align: center;
            font-size: 1.35rem;
            font-weight: 600;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .otp-box:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color), transparent 85%);
        }
        .otp-box.filled {
            border-color: var(--primary-color);
            background: color-mix(in srgb, var(--primary-color), transparent 96%);
        }
        .otp-wrap {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .resend-link {
            color: var(--primary-color);
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .resend-link:hover { opacity: 0.85; }
        .resend-link.disabled {
            pointer-events: none;
            color: #999;
        }
        .card-otp {
            max-width: 420px;
            width: 100%;
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-otp:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important;
        }
        .btn-verify {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-verify:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px color-mix(in srgb, var(--primary-color), transparent 65%);
        }
        .btn-verify:active {
            transform: translateY(0);
        }
        .otp-error {
            font-size: 0.875rem;
            color: #dc3545;
            margin-top: 0.5rem;
            opacity: 0;
            transition: opacity 0.25s ease;
        }
        .otp-error.show { opacity: 1; }
        .otp-error.text-success { color: #198754; }
        @media (max-width: 400px) {
            .otp-box { width: 42px; height: 48px; font-size: 1.2rem; }
            .otp-wrap { gap: 8px; }
        }
    </style>
</head>

<body>
<div class="body-wrapper">

    @include('main_view.include.header')

    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
        <div class="card shadow-sm p-4 card-otp">

            <div class="text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-shield-alt text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h3>Verify your account</h3>

            </div>

            @if(session('error'))
                <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success py-2 small">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger py-2 small">
                    @foreach($errors->all() as $err) {{ $err }} @endforeach
                </div>
            @endif

            <form id="otpForm" method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <input type="hidden" name="otp" id="otpValue" value="">
                <div class="otp-wrap mb-4">
                    @for($i = 0; $i < 6; $i++)
                        <input type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code"
                               class="form-control otp-box" data-index="{{ $i }}" aria-label="Digit {{ $i + 1 }}">
                    @endfor
                </div>

                <p id="otpError" class="otp-error text-center"></p>

                <button type="submit" class="btn btn-primary w-100 btn-verify py-2" id="verifyBtn">
                    <i class="fas fa-check me-2"></i>Verify
                </button>

                <p class="text-center mt-4 mb-0 small text-muted">
                    Didn't receive the code?
                    <span id="resendBtn" class="resend-link fw-medium">Resend OTP</span>
                    <span id="resendTimer" class="d-none">Resend in <span id="countdown">60</span>s</span>
                </p>

                <div class="text-center mt-3">
                    <a href="{{ route('user_register') }}" class="text-muted small">Back to Register</a>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
(function() {
    const inputs = document.querySelectorAll('.otp-box');
    const form = document.getElementById('otpForm');
    const errorEl = document.getElementById('otpError');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const resendTimer = document.getElementById('resendTimer');
    const countdownEl = document.getElementById('countdown');

    function showError(msg) {
        errorEl.textContent = msg;
        errorEl.classList.add('show');
        setTimeout(function() { errorEl.classList.remove('show'); }, 3000);
    }

    function setFilledState() {
        inputs.forEach(function(inp) {
            inp.classList.toggle('filled', inp.value.length > 0);
        });
    }

    inputs.forEach(function(inp, i) {
        inp.addEventListener('input', function() {
            var val = this.value.replace(/\D/g, '');
            this.value = val.slice(0, 1);
            setFilledState();
            if (val && i < inputs.length - 1) inputs[i + 1].focus();
        });

        inp.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && i > 0) {
                inputs[i - 1].focus();
            }
        });

        inp.addEventListener('paste', function(e) {
            e.preventDefault();
            var pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            for (var j = 0; j < pasted.length && j < inputs.length; j++) {
                inputs[j].value = pasted[j];
            }
            setFilledState();
            inputs[Math.min(pasted.length, inputs.length - 1)].focus();
        });

        inp.addEventListener('focus', setFilledState);
        inp.addEventListener('blur', setFilledState);
    });

    form.addEventListener('submit', function(e) {
        var code = Array.from(inputs).map(function(i) { return i.value; }).join('');
        if (code.length !== 6) {
            e.preventDefault();
            showError('Please enter all 6 digits.');
            return;
        }
        document.getElementById('otpValue').value = code;
        verifyBtn.disabled = true;
        verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';
    });

    var countdown = 60;
    var timerId = null;
    function startResendTimer() {
        countdown = 60;
        resendBtn.classList.add('d-none');
        resendTimer.classList.remove('d-none');
        countdownEl.textContent = countdown;
        timerId = setInterval(function() {
            countdown--;
            countdownEl.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(timerId);
                resendTimer.classList.add('d-none');
                resendBtn.classList.remove('d-none');
            }
        }, 1000);
    }
    startResendTimer();

    resendBtn.addEventListener('click', function() {
        if (resendBtn.classList.contains('disabled')) return;
        resendBtn.classList.add('disabled');
        var token = document.querySelector('input[name="_token"]').value;
        fetch('{{ route("otp.resend") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: JSON.stringify({})
        }).then(function(r) { return r.json(); }).then(function(data) {
            if (data.success) {
                startResendTimer();
                if (errorEl) {
                    errorEl.textContent = data.message || 'New code sent!';
                    errorEl.classList.remove('text-danger'); errorEl.classList.add('text-success', 'show');
                    setTimeout(function() { errorEl.classList.remove('show'); }, 4000);
                }
            } else {
                showError(data.message || 'Failed to resend.');
            }
        }).catch(function() { showError('Failed to resend. Try again.'); }).finally(function() {
            resendBtn.classList.remove('disabled');
        });
    });

    if (inputs.length) inputs[0].focus();
})();
</script>

</body>
</html>
