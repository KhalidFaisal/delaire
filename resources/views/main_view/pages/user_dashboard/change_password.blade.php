@extends('main_view.pages.user_dashboard.layout')

@section('title', 'Change Password')

@section('content')
<h4 class="mb-4">Change Password</h4>
<form action="{{ route('user.update_password') }}" method="POST">
    @csrf
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            @foreach($errors->all() as $e) {{ $e }} @endforeach
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control" required placeholder="Enter current password">
    </div>

    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" required placeholder="Minimum 8 characters">
    </div>

    <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="new_password_confirmation" class="form-control" required placeholder="Repeat new password">
    </div>

    <button type="submit" class="btn btn-primary">Update Password</button>
</form>
@endsection
