@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <span class="text-muted fw-light">Dashboard /</span> {{ $title }}
            </h4>
            <p class="mb-0">Kelola semua notifikasi Anda</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="btn-group" role="group">
            @if(Auth::user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check-double me-1"></i>
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif

            @if(Auth::user()->notifications->count() > 0)
                <button type="button" 
                        class="btn btn-danger" 
                        onclick="confirmDeleteAll()">
                    <i class="bx bx-trash me-1"></i>
                    Hapus Semua
                </button>
            @endif
        </div>
    </div>

    <!-- Success/Error Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Notifications Card -->
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Semua Notifikasi</h5>
            <div>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="badge bg-label-danger me-2">
                        {{ Auth::user()->unreadNotifications->count() }} Belum Dibaca
                    </span>
                @endif
                <span class="badge bg-label-primary">{{ Auth::user()->notifications->count() }} Total</span>
            </div>
        </div>

        <div class="card-body p-0">
            @forelse($notifications as $notification)
                <div class="notification-item border-bottom {{ $notification->read_at ? '' : 'bg-label-primary' }} p-3">
                    <div class="d-flex align-items-start">
                        <!-- Icon -->
                        <div class="flex-shrink-0 me-3">
                            @if(isset($notification->data['type']))
                                @if($notification->data['type'] === 'new_task' || $notification->data['type'] === 'new_task_added')
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-info">
                                            <i class="bx bx-task bx-sm"></i>
                                        </span>
                                    </div>
                                @elseif($notification->data['type'] === 'task_completed')
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-success">
                                            <i class="bx bx-check-circle bx-sm"></i>
                                        </span>
                                    </div>
                                @elseif($notification->data['type'] === 'room_completed')
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-success">
                                            <i class="bx bx-medal bx-sm"></i>
                                        </span>
                                    </div>
                                @elseif($notification->data['type'] === 'reminder')
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-warning">
                                            <i class="bx bx-alarm bx-sm"></i>
                                        </span>
                                    </div>
                                @else
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-secondary">
                                            <i class="bx bx-bell bx-sm"></i>
                                        </span>
                                    </div>
                                @endif
                            @elseif(isset($notification->data['icon']))
                                @if($notification->data['icon'] === 'bi-door-open')
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            <i class="bx bx-door-open bx-sm"></i>
                                        </span>
                                    </div>
                                @elseif($notification->data['icon'] === 'bi-x-circle')
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-danger">
                                            <i class="bx bx-x-circle bx-sm"></i>
                                        </span>
                                    </div>
                                @else
                                    <div class="avatar">
                                        <span class="avatar-initial rounded-circle bg-label-secondary">
                                            <i class="bx bx-bell bx-sm"></i>
                                        </span>
                                    </div>
                                @endif
                            @else
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-secondary">
                                        <i class="bx bx-bell bx-sm"></i>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0">{{ $notification->data['title'] ?? 'Notifikasi' }}</h6>
                                @if(!$notification->read_at)
                                    <span class="badge bg-primary badge-sm ms-2">Baru</span>
                                @endif
                            </div>
                            
                            <p class="mb-2 text-muted">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            <!-- Additional Info -->
                            @if(isset($notification->data['room_name']))
                                <div class="mb-2">
                                    <span class="badge bg-label-secondary">
                                        <i class="bx bx-building bx-xs"></i> {{ $notification->data['room_name'] }}
                                    </span>
                                </div>
                            @elseif(isset($notification->data['room_id']))
                                @php
                                    $room = \App\Models\Ruangan::find($notification->data['room_id']);
                                @endphp
                                @if($room)
                                    <div class="mb-2">
                                        <span class="badge bg-label-secondary">
                                            <i class="bx bx-building bx-xs"></i> {{ $room->name }}
                                        </span>
                                    </div>
                                @endif
                            @endif

                            @if(isset($notification->data['assigned_date']))
                                <div class="mb-2">
                                    <span class="badge bg-label-info">
                                        <i class="bx bx-calendar bx-xs"></i> {{ \Carbon\Carbon::parse($notification->data['assigned_date'])->format('d M Y') }}
                                    </span>
                                </div>
                            @endif

                            @if(isset($notification->data['assigned_by']))
                                <div class="mb-2">
                                    <span class="badge bg-label-warning">
                                        <i class="bx bx-user bx-xs"></i> Oleh: {{ $notification->data['assigned_by'] }}
                                    </span>
                                </div>
                            @endif

                            @if(isset($notification->data['removed_by']))
                                <div class="mb-2">
                                    <span class="badge bg-label-danger">
                                        <i class="bx bx-user-x bx-xs"></i> Dihapus oleh: {{ $notification->data['removed_by'] }}
                                    </span>
                                </div>
                            @endif

                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <small class="text-muted">
                                    <i class="bx bx-time-five"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                    <span class="text-muted">• {{ $notification->created_at->format('d M Y, H:i') }}</span>
                                </small>

                                <!-- Actions -->
                                <div class="btn-group btn-group-sm" role="group">
                                    @if(isset($notification->data['url']))
                                        <a href="{{ $notification->data['url'] }}" 
                                           class="btn btn-outline-primary btn-sm"
                                           onclick="markAsRead('{{ $notification->id }}')">
                                            <i class="bx bx-right-arrow-alt"></i> Lihat
                                        </a>
                                    @elseif(isset($notification->data['action_url']))
                                        <a href="{{ $notification->data['action_url'] }}" 
                                           class="btn btn-outline-primary btn-sm"
                                           onclick="markAsRead('{{ $notification->id }}')">
                                            <i class="bx bx-right-arrow-alt"></i> Lihat Tugas
                                        </a>
                                    @endif

                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                <i class="bx bx-check"></i> Tandai Dibaca
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('notifications.destroy', $notification->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Hapus notifikasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-5 text-center">
                    <div class="avatar avatar-xl mb-3 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-secondary">
                            <i class="bx bx-bell-off bx-lg"></i>
                        </span>
                    </div>
                    <h5 class="mb-1">Tidak Ada Notifikasi</h5>
                    <p class="text-muted mb-0">Anda tidak memiliki notifikasi saat ini</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $notifications->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Hidden form for delete all -->
<form id="deleteAllForm" action="{{ route('notifications.delete-all') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    });
}

function confirmDeleteAll() {
    if (confirm('Apakah Anda yakin ingin menghapus SEMUA notifikasi? Tindakan ini tidak dapat dibatalkan!')) {
        document.getElementById('deleteAllForm').submit();
    }
}
</script>

<style>
.notification-item {
    transition: all 0.3s ease;
}

.notification-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.notification-item:last-child {
    border-bottom: none !important;
}

/* Animation untuk notifikasi baru */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notification-item {
    animation: fadeIn 0.3s ease;
}

.btn-group > form {
    display: inline-block;
}

.btn-group > form > button {
    border-radius: 0;
}
</style>
@endsection