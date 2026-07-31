@extends('backend.layout.template')
@section('title', 'Admin Activity Logs')
@section('body-content')

<div class="container card">
    <div class="content-container p-4">
        
        <h3 class="text-center">Admin Activity Logs</h3>
        <p class="text-muted text-center mb-4">Track all administrative changes and actions across the system.</p>
        
        <!-- Filters Grid -->
        <form action="{{ route('admin.logs.index') }}" method="GET" class="row g-3 mb-4 align-items-end">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <label class="form-label fw-bold" for="search">Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="fa fa-search"></i></span>
                    <input class="form-control" id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Search admin, action, desc...">
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <label class="form-label fw-bold" for="action_type">Action Category</label>
                <select class="form-select" id="action_type" name="action_type">
                    <option value="">All Categories</option>
                    @foreach($actionTypes as $type)
                        <option value="{{ $type }}" {{ request('action_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <label class="form-label fw-bold" for="start_date">Start Date</label>
                <input class="form-control" id="start_date" type="date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <label class="form-label fw-bold" for="end_date">End Date</label>
                <input class="form-control" id="end_date" type="date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6 d-flex gap-2">
                <button class="btn btn-primary d-flex align-items-center justify-content-center" type="submit" title="Filter" style="height: 38px; width: 38px; min-width: 38px;">
                    <i class="fa fa-arrow-right"></i>
                </button>
                <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary d-flex align-items-center justify-content-center" title="Reset Filters" style="height: 38px; width: 38px; min-width: 38px;">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
        </form>

        <!-- Logs Table -->
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 15%;">Admin</th>
                        <th style="width: 15%;">Action</th>
                        <th style="width: 35%;">Description</th>
                        <th style="width: 10%;">IP Address</th>
                        <th style="width: 12%;">Date & Time</th>
                        <th style="width: 8%;" class="text-center">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>
                                <strong>{{ $log->admin_name }}</strong>
                                @if($log->admin)
                                    <br><small class="text-muted">{{ $log->admin->email }}</small>
                                @else
                                    <br><small class="text-danger">(Deleted Admin)</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = 'bg-secondary';
                                    if (str_contains($log->action, 'Created') || str_contains($log->action, 'Stock In')) {
                                        $badgeClass = 'bg-success';
                                    } elseif (str_contains($log->action, 'Deleted') || str_contains($log->action, 'Cancel') || str_contains($log->action, 'Damage')) {
                                        $badgeClass = 'bg-danger';
                                    } elseif (str_contains($log->action, 'Updated') || str_contains($log->action, 'Update')) {
                                        $badgeClass = 'bg-warning text-dark';
                                    } elseif (str_contains($log->action, 'Login')) {
                                        $badgeClass = 'bg-info text-dark';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $log->action }}</span>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td><code class="text-dark">{{ $log->ip_address ?? 'N/A' }}</code></td>
                            <td>
                                <span>{{ $log->created_at->format('d M Y') }}</span><br>
                                <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                            </td>
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-outline-primary btn-xs view-log-details" 
                                        data-id="{{ $log->id }}"
                                        data-admin="{{ $log->admin_name }}"
                                        data-action="{{ $log->action }}"
                                        data-ip="{{ $log->ip_address }}"
                                        data-ua="{{ $log->user_agent }}"
                                        data-date="{{ $log->created_at->format('d M Y h:i:s A') }}"
                                        data-payload="{{ json_encode($log->details, JSON_PRETTY_PRINT) }}">
                                    <i class="fa fa-eye"></i> Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fa fa-info-circle fa-2x mb-2"></i><br>
                                No activity logs found matching the filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} entries
            </div>
            <div>
                {{ $logs->links() }}
            </div>
        </div>
        
    </div>
</div>

<!-- Modal for Log Details -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailsModalLabel"><i class="fa fa-history"></i> Log Event Details</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close" style="border:none; background:transparent; font-size: 1.5rem;">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <strong>Admin:</strong> <span id="modalAdmin"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Action Category:</strong> <span id="modalAction" class="badge bg-secondary"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>IP Address:</strong> <code id="modalIp"></code>
                    </div>
                    <div class="col-md-6">
                        <strong>Timestamp:</strong> <span id="modalDate"></span>
                    </div>
                    <div class="col-12">
                        <strong>User Agent:</strong> <br><small class="text-muted" id="modalUa"></small>
                    </div>
                </div>
                
                <hr>
                
                <h6>Structured Payload / Action Details:</h6>
                <div class="bg-light p-3 rounded" style="max-height: 350px; overflow-y: auto;">
                    <pre><code id="modalPayload" class="text-dark" style="font-family: monospace; white-space: pre-wrap;"></code></pre>
                </div>
            </div>
            <div class="modal-header d-flex justify-content-end pb-3 pt-0" style="border-top: none;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.view-log-details');
    const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));

    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('modalAdmin').innerText = this.getAttribute('data-admin');
            document.getElementById('modalAction').innerText = this.getAttribute('data-action');
            document.getElementById('modalIp').innerText = this.getAttribute('data-ip') || 'N/A';
            document.getElementById('modalDate').innerText = this.getAttribute('data-date');
            document.getElementById('modalUa').innerText = this.getAttribute('data-ua') || 'N/A';

            let payloadRaw = this.getAttribute('data-payload');
            let payloadText = 'No extra payload details available.';

            try {
                if (payloadRaw && payloadRaw !== 'null' && payloadRaw !== '""' && payloadRaw !== '[]' && payloadRaw !== '{}') {
                    // Try parsing and formatting if not already prettified
                    const parsed = JSON.parse(payloadRaw);
                    payloadText = JSON.stringify(parsed, null, 4);
                }
            } catch (e) {
                if (payloadRaw) {
                    payloadText = payloadRaw;
                }
            }

            document.getElementById('modalPayload').innerText = payloadText;
            modal.show();
        });
    });
});
</script>
@endsection
