@extends('main_view.pages.user_dashboard.layout')

@section('title', 'Edit Profile')

@section('content')
<h4 class="mb-4">Edit Profile</h4>
<form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            @foreach($errors->all() as $e) {{ $e }} @endforeach
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Profile image</label>
        <div class="d-flex align-items-center gap-3 mb-2">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="" class="lazy-image rounded-circle" width="80" height="80" style="object-fit:cover;" loading="lazy" >
            @else
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:80px;height:80px;font-size:28px;">
                    {{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}
                </div>
            @endif
            <div>
                <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*">
                <small class="text-muted">JPEG, PNG, GIF, WebP. Max 2MB.</small>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
        <small class="text-muted">Email cannot be changed.</small>
    </div>

    <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="3" placeholder="Your address">{{ old('address', $user->address) }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Shipping address</label>
        <textarea name="shipping_address" class="form-control" rows="3" placeholder="Default shipping address">{{ old('shipping_address', $user->shipping_address) }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Save changes</button>
</form>
@endsection
