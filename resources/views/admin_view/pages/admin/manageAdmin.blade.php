@extends('backend.layout.template')
@section('title')
    Dashboard
@endsection
@section('body-content')

@php 
                $admin = Auth::user();
              @endphp

              @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="container card">
    <div class="content-container p-4">
        <h3 class="text-center">Update Admin Profile</h3><br>
        
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success">
                Profile updated successfully.
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            
            <div class="form-group mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $admin->name) }}" 
                       required 
                       autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $admin->email) }}" 
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
    
            <div class="form-group mb-4">
                <label for="password" class="form-label">New Password (Optional)</label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       placeholder="Leave blank to keep current password" 
                       minlength="8" 
                       autocomplete="new-password">
                <small class="text-muted">Min 8 characters.</small>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">Update Profile</button>
            </div>
        </form>
    </div>
</div><br>



  <!-- Add Bootstrap JS and jQuery scripts (optional but may be required for certain features) -->
 

@endsection

@section('script')
    <script>

    </script>
@endsection