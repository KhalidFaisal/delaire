@extends('backend.layout.template')
@section('title')
    App Settings
@endsection
@section('body-content')

@if(session('success') || request()->has('success'))
    <div class="alert alert-success">{{ session('success', 'App Settings updated successfully!') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container card">
    <div class="content-container p-4">
        <h3 class="text-center">Manage App Settings</h3><br>
        <p class="text-muted text-center">These values directly modify your application environment configuration.</p>
        <form action="{{ route('update.app.settings') }}" method="POST" id="appSettingsForm">
            @csrf
            
            <h5 class="mt-4 mb-3">General Application Info</h5>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label class="form-label" for="APP_NAME">App Name</label>
                    <input class="form-control" id="APP_NAME" type="text" name="APP_NAME" value="{{ $settings['APP_NAME'] }}" required>
                </div>
            </div>

            <h5 class="mt-4 mb-3">SMTP Mail Configuration</h5>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label" for="MAIL_FROM_ADDRESS">Mail From Address</label>
                    <input class="form-control" id="MAIL_FROM_ADDRESS" type="email" name="MAIL_FROM_ADDRESS" value="{{ $settings['MAIL_FROM_ADDRESS'] }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label" for="MAIL_HOST">Mail Host</label>
                    <input class="form-control" id="MAIL_HOST" type="text" name="MAIL_HOST" value="{{ $settings['MAIL_HOST'] }}" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label" for="MAIL_PORT">Mail Port</label>
                    <input class="form-control" id="MAIL_PORT" type="number" name="MAIL_PORT" value="{{ $settings['MAIL_PORT'] }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label" for="MAIL_ENCRYPTION">Mail Encryption</label>
                    <input class="form-control" id="MAIL_ENCRYPTION" type="text" name="MAIL_ENCRYPTION" value="{{ $settings['MAIL_ENCRYPTION'] }}" required placeholder="tls, ssl, etc.">
                </div>
            </div>

            <h5 class="mt-4 mb-3">Google Login Credentials</h5>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label class="form-label" for="GOOGLE_CLIENT_ID">Google Client ID</label>
                    <input class="form-control" id="GOOGLE_CLIENT_ID" type="text" name="GOOGLE_CLIENT_ID" value="{{ $settings['GOOGLE_CLIENT_ID'] }}">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 form-group">
                    <label class="form-label" for="GOOGLE_REDIRECT_URL">Google Redirect URL</label>
                    <input class="form-control" id="GOOGLE_REDIRECT_URL" type="url" name="GOOGLE_REDIRECT_URL" value="{{ $settings['GOOGLE_REDIRECT_URL'] }}">
                </div>
            </div>

            <br>
            <div class="text-center mt-3">
                <button class="btn btn-primary" type="submit" id="saveBtn">Save App Settings</button>
            </div>
        </form>
    </div>
</div><br>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('appSettingsForm');
    if(form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('saveBtn');
            btn.disabled = true;
            btn.innerHTML = 'Saving & Restarting Server...';
            
            try {
                await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'Accept': 'application/json'
                    }
                });
            } catch (error) {
                console.log("Expected network drop due to config clear restarting the artisan server");
            }
            
            // Wait 3 seconds for the server to spin back up, then reload with success message
            setTimeout(() => {
                window.location.href = window.location.pathname + '?success=1';
            }, 3000);
        });
    }
});
</script>
@endsection
