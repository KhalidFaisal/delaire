@extends('backend.layout.template')

@section('title', 'Manage Admins')

@section('body-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Manage Admins</h5>
                    @if(Auth::guard('admin')->user()->role === 'super_admin')
                        <a href="{{ route('admin.manage.create') }}" class="btn btn-primary">Add New Admin</a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $admin)
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($admin->role) }}</span></td>
                                    <td>{{ $admin->phone ?? 'N/A' }}</td>
                                    <td>
                                        @if($admin->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.manage.edit', $admin->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        @if(in_array(Auth::guard('admin')->user()->role, ['super_admin', 'admin']) && Auth::guard('admin')->id() !== $admin->id)
                                            <form action="{{ route('admin.manage.destroy', $admin->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
