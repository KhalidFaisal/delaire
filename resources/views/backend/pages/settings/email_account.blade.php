@extends('backend.layout.template')
@section('title')
    Manage Email Account
@endsection
@section('body-content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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
        <h3 class="text-center">Manage Notification Email Account</h3><br>
        <p class="text-muted text-center">Configure the email address(es) that should receive admin notifications when an order is placed.</p>
        <form action="{{ route('update.email.account') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-12 form-group">
                    <label class="form-label" for="admin_notification_email">Admin Notification Email Address(es)</label>
                    <input class="form-control" id="admin_notification_email" type="text" name="admin_notification_email" value="{{ $setting->admin_notification_email }}" placeholder="e.g. admin@example.com, notifications@example.com">
                    <small class="text-muted">Orders placed by users or admins will trigger notification emails sent to these addresses. Separate multiple emails with commas.</small>
                </div>
            </div>

            <br>
            <div class="text-center">
                <button class="btn btn-primary" type="submit">Update Email Account Settings</button>
            </div>
        </form>
    </div>
</div><br>
@endsection
