@extends('backend.layout.template')

@section('title', 'All Notifications')

@section('body-content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Notifications</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>All Notifications</h5>
                    <div class="card-header-right">
                         <button class="btn btn-primary btn-sm" onclick="markAllRead()">Mark All as Read</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="notification-list">
                        @forelse($notifications as $notification)
                        <div class="media p-3 mb-2 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}">
                            @php
                                $data = $notification->data;
                                $icon = 'bell';
                                $color = 'primary';
                                if($data['type'] ?? '' == 'order') { $icon = 'shopping-bag'; $color = 'success'; }
                                elseif($data['type'] ?? '' == 'return') { $icon = 'rotate-ccw'; $color = 'warning'; }
                                elseif($data['type'] ?? '' == 'stock') { $icon = 'alert-triangle'; $color = 'danger'; }
                                elseif($data['type'] ?? '' == 'review') { $icon = 'star'; $color = 'info'; }
                            @endphp
                            <div class="media-body">
                                <h6 class="mt-0 txt-{{ $color }} f-w-600">
                                    <i data-feather="{{ $icon }}" class="mr-2"></i>
                                    {{ $data['message'] ?? 'Notification' }}
                                    <span class="float-right f-12 text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                                </h6>
                                <p class="mb-0">
                                    @if(!empty($data['link']))
                                        @php
                                            $linkPath = $data['link'];
                                            if (filter_var($linkPath, FILTER_VALIDATE_URL)) {
                                                $parsed = parse_url($linkPath);
                                                $linkPath = ($parsed['path'] ?? '') . (isset($parsed['query']) ? '?'.$parsed['query'] : '');
                                            }
                                        @endphp
                                        <a href="{{ $linkPath }}" class="text-primary" onclick="markItemRead('{{ $notification->id }}')">View Details</a>
                                    @endif
                                    @if(is_null($notification->read_at))
                                        <span class="badge badge-secondary ml-2">New</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center p-5">
                            <p>No notifications found.</p>
                        </div>
                        @endforelse
                    </div>
                    <div class="mt-3">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function markAllRead() {
        $.post("{{ route('admin.notifications.read') }}", {
            _token: "{{ csrf_token() }}"
        }, function(){
            location.reload();
        });
    }
    function markItemRead(id) {
        $.post("{{ route('admin.notifications.read') }}", {
            id: id,
            _token: "{{ csrf_token() }}"
        });
    }
</script>
@endsection
