@extends('backend.layout.template')

@section('title', 'Manage Roles')

@section('body-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Manage Roles & Permissions</h5>
                    <div>
                        <a href="{{ route('admin.manage.index') }}" class="btn btn-light btn-sm me-2"><i class="fa fa-arrow-left"></i> Back to Admins</a>
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New Role</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 8%;">ID</th>
                                    <th>Role Name</th>
                                    <th>Role Slug</th>
                                    <th>Allowed Permissions</th>
                                    <th style="width: 25%;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td><strong>{{ $role->name }}</strong></td>
                                    <td><code>{{ $role->slug }}</code></td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ is_array($role->permissions) ? count($role->permissions) : 0 }} modules allowed
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($role->slug !== 'super_admin')
                                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary btn-sm me-1">
                                                <i class="fa fa-edit"></i> Edit Permissions
                                            </a>
                                            @if(!in_array($role->slug, ['super_admin', 'admin', 'manager', 'editor']))
                                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this role?')">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-muted"><i class="fa fa-shield"></i> Full Access (Super Admin)</span>
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
