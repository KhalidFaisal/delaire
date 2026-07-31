@extends('backend.layout.template')

@section('title', 'Edit Role')

@section('body-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Edit Role & Permissions</h5>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-light btn-sm"><i class="fa fa-arrow-left"></i> Back to Roles</a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Role Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
                            <small class="text-muted">Slug (cannot be modified to preserve existing users): <code>{{ $role->slug }}</code></small>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Configure Permissions</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkAllPermissions">
                                <label class="form-check-label fw-bold" for="checkAllPermissions">Select All Options</label>
                            </div>
                        </div>

                        <div class="row g-4">
                            @foreach($groups as $groupName => $permissions)
                            @php
                                $groupSlug = Str::slug($groupName);
                                $hasAllInGroup = true;
                                foreach($permissions as $slug => $label) {
                                    if(!in_array($slug, $role->permissions ?? [])) {
                                        $hasAllInGroup = false;
                                        break;
                                    }
                                }
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded p-3 bg-light h-100">
                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                        <span class="fw-bold text-primary">{{ $groupName }}</span>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input group-check" type="checkbox" id="group_{{ $groupSlug }}" data-group="{{ $groupSlug }}" {{ $hasAllInGroup ? 'checked' : '' }}>
                                            <label class="form-check-label text-muted small" for="group_{{ $groupSlug }}">Select Group</label>
                                        </div>
                                    </div>
                                    <div class="permission-list" id="list_{{ $groupSlug }}">
                                        @foreach($permissions as $slug => $label)
                                        <div class="form-check my-2">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $slug }}" id="perm_{{ $slug }}" data-group="{{ $groupSlug }}" {{ in_array($slug, $role->permissions ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $slug }}">{{ $label }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">Update Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAllPermissions');
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    const groupCheckboxes = document.querySelectorAll('.group-check');

    // Run initial state setup
    updateCheckAllState();

    // Toggle All checkboxes
    checkAll.addEventListener('change', function () {
        const isChecked = this.checked;
        checkboxes.forEach(cb => cb.checked = isChecked);
        groupCheckboxes.forEach(cb => cb.checked = isChecked);
    });

    // Toggle Group checkboxes
    groupCheckboxes.forEach(groupCb => {
        groupCb.addEventListener('change', function () {
            const isChecked = this.checked;
            const groupName = this.getAttribute('data-group');
            const groupPermissionCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${groupName}"]`);
            groupPermissionCheckboxes.forEach(cb => cb.checked = isChecked);
            updateCheckAllState();
        });
    });

    // Individual checkboxes state update
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const groupName = this.getAttribute('data-group');
            updateGroupCheckState(groupName);
            updateCheckAllState();
        });
    });

    function updateGroupCheckState(groupName) {
        const groupCb = document.querySelector(`.group-check[data-group="${groupName}"]`);
        const groupPermissionCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${groupName}"]`);
        const checkedCount = document.querySelectorAll(`.permission-checkbox[data-group="${groupName}"]:checked`).length;
        if (groupCb) {
            groupCb.checked = (checkedCount === groupPermissionCheckboxes.length);
        }
    }

    function updateCheckAllState() {
        const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
        if (checkAll) {
            checkAll.checked = (checkedCount === checkboxes.length);
        }
    }
});
</script>
@endsection
