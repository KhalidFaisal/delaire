@extends('backend.layout.template')

@section('title', 'Edit Admin')

@section('body-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Edit Admin</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.manage.update', $admin->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $admin->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password (Leave blank to keep current)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin" {{ $admin->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="super_admin" {{ $admin->role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="manager" {{ $admin->role == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="editor" {{ $admin->role == 'editor' ? 'selected' : '' }}>Editor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $admin->phone }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
