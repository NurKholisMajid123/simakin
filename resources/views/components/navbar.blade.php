<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
  id="layout-navbar">

  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    <!-- Waktu Real-time - Kiri -->
    <div class="navbar-nav align-items-center me-auto">
      <div class="nav-item d-flex align-items-center">
        <i class="bx bx-time-five fs-4 lh-0 me-2"></i>
        <div id="current-time" class="fw-semibold"></div>
      </div>
    </div>

    <!-- Right Side Items -->
    <ul class="navbar-nav flex-row align-items-center ms-auto">

      <!-- Notifications -->
      <li class="nav-item navbar-dropdown dropdown me-3">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="position-relative">
            <i class="bx bx-bell bx-sm"></i>
            @if(auth()->user()->unreadNotifications->count() > 0)
              <span class="badge badge-center rounded-pill bg-danger position-absolute top-0 start-100 translate-middle"
                style="font-size: 0.65rem; padding: 0.25rem 0.4rem;">
                {{ auth()->user()->unreadNotifications->count() > 9 ? '9+' : auth()->user()->unreadNotifications->count() }}
              </span>
            @endif
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
          <li>
            <div class="dropdown-header d-flex align-items-center justify-content-between py-3 px-3">
              <h6 class="mb-0">Notifikasi</h6>
              @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-text-primary p-0">
                    <small>Tandai semua dibaca</small>
                  </button>
                </form>
              @endif
            </div>
          </li>
          <li>
            <hr class="dropdown-divider m-0">
          </li>

          <div class="notification-scroll">
            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
              <li>
                <a class="dropdown-item notification-item {{ $notification->read_at ? 'bg-light' : 'bg-label-primary' }}"
                  href="{{ route('notifications.read', $notification->id) }}">
                  <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-2">
                      @if(isset($notification->data['type']))
                        @if($notification->data['type'] === 'new_task')
                          <span class="avatar avatar-sm">
                            <span class="avatar-initial rounded-circle bg-label-info">
                              <i class="bx bx-task"></i>
                            </span>
                          </span>
                        @elseif($notification->data['type'] === 'task_completed')
                          <span class="avatar avatar-sm">
                            <span class="avatar-initial rounded-circle bg-label-success">
                              <i class="bx bx-check-circle"></i>
                            </span>
                          </span>
                        @elseif($notification->data['type'] === 'room_completed')
                          <span class="avatar avatar-sm">
                            <span class="avatar-initial rounded-circle bg-label-success">
                              <i class="bx bx-medal"></i>
                            </span>
                          </span>
                        @elseif($notification->data['type'] === 'reminder')
                          <span class="avatar avatar-sm">
                            <span class="avatar-initial rounded-circle bg-label-warning">
                              <i class="bx bx-alarm"></i>
                            </span>
                          </span>
                        @elseif($notification->data['type'] === 'new_room')
                          <span class="avatar avatar-sm">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                              <i class="bx bx-building-house"></i>
                            </span>
                          </span>
                        @else
                          <span class="avatar avatar-sm">
                            <span class="avatar-initial rounded-circle bg-label-secondary">
                              <i class="bx bx-bell"></i>
                            </span>
                          </span>
                        @endif
                      @endif
                    </div>
                    <div class="flex-grow-1 notification-content">
                      <h6 class="mb-1 notification-title">{{ $notification->data['title'] ?? 'Notifikasi' }}</h6>
                      <p class="mb-1 notification-message">
                        {{ Str::limit($notification->data['message'] ?? '', 80) }}
                      </p>
                      <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                  </div>
                </a>
              </li>
              @if(!$loop->last)
                <li>
                  <hr class="dropdown-divider m-0">
                </li>
              @endif
            @empty
              <li>
                <div class="dropdown-item text-center py-4">
                  <i class="bx bx-bell-off bx-sm text-muted mb-2"></i>
                  <p class="mb-0 text-muted">Tidak ada notifikasi</p>
                </div>
              </li>
            @endforelse
          </div>

          @if(auth()->user()->notifications->count() > 0)
            <li>
              <hr class="dropdown-divider m-0">
            </li>
            <li>
              <div class="dropdown-item text-center py-2">
                <a href="{{ route('notifications.index') }}" class="text-primary">
                  <small>Lihat semua notifikasi</small>
                </a>
              </div>
            </li>
          @endif
        </ul>
      </li>

      <!-- Quick Access -->
      <li class="nav-item navbar-dropdown dropdown me-3">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <i class="bx bx-grid-alt bx-sm"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" style="width: 280px;">
          <li>
            <div class="dropdown-header py-3">
              <h6 class="mb-0">Quick Access</h6>
            </div>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          @if(auth()->user()->role === 'admin')
            <!-- Quick Access untuk Admin -->
            <li>
              <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                <i class="bx bx-home-circle me-2"></i>
                <span class="align-middle">Dashboard</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('admin.cleaning-records.index') }}">
                <i class="bx bx-clipboard me-2"></i>
                <span class="align-middle">Laporan Kebersihan</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('admin.ruangan.index') }}">
                <i class="bx bx-building me-2"></i>
                <span class="align-middle">Kelola Ruangan</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('admin.tugas.index') }}">
                <i class="bx bx-list-check me-2"></i>
                <span class="align-middle">Kelola Tugas</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                <i class="bx bx-user me-2"></i>
                <span class="align-middle">Kelola OB</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('admin.cleaning-records.export') }}">
                <i class="bx bx-download me-2"></i>
                <span class="align-middle">Export Laporan</span>
              </a>
            </li>
          @else
            <!-- Quick Access untuk OB -->
            <li>
              <a class="dropdown-item" href="{{ route('ob.dashboard') }}">
                <i class="bx bx-home-circle me-2"></i>
                <span class="align-middle">Dashboard</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('ob.cleaning-records.index') }}">
                <i class="bx bx-task me-2"></i>
                <span class="align-middle">Tugas Kebersihan</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('notifications.index') }}">
                <i class="bx bx-bell me-2"></i>
                <span class="align-middle">Semua Notifikasi</span>
              </a>
            </li>
          @endif
        </ul>
      </li>

      <!-- User Profile -->
      <li class="nav-item navbar-dropdown dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <span class="avatar-initial rounded-circle bg-label-primary">
              {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </span>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                      {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                  <small
                    class="text-muted">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Office Boy' }}</small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <i class="bx bx-user me-2"></i>
              <span class="align-middle">Profil Saya</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <i class="bx bx-cog me-2"></i>
              <span class="align-middle">Pengaturan</span>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
              @csrf
              <button type="submit" class="dropdown-item">
                <i class="bx bx-power-off me-2"></i>
                <span class="align-middle">Keluar</span>
              </button>
            </form>
          </li>
        </ul>
      </li>
      <!--/ User -->
    </ul>
  </div>
</nav>

<!-- Script untuk Waktu Real-time -->
<script>
  function updateTime() {
    const now = new Date();
    const options = {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    };

    const timeString = now.toLocaleDateString('id-ID', options);
    document.getElementById('current-time').textContent = timeString;
  }

  // Update waktu setiap detik
  updateTime();
  setInterval(updateTime, 1000);

  // Auto refresh notification count setiap 30 detik
  setInterval(function () {
    fetch('{{ route("notifications.unread-count") }}')
      .then(response => response.json())
      .then(data => {
        const badge = document.querySelector('.badge-center');
        if (data.count > 0) {
          if (badge) {
            badge.textContent = data.count > 9 ? '9+' : data.count;
          }
        } else {
          if (badge) {
            badge.remove();
          }
        }
      });
  }, 30000);
</script>

<style>
  /* Custom styling untuk notification badge */
  .badge-center {
    min-width: 18px;
    min-height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* NOTIFICATION DROPDOWN - FIXED */
  .notification-dropdown {
    width: 380px !important;
    max-width: 95vw;
    padding: 0;
    overflow: hidden;
  }

  .notification-scroll {
    max-height: 400px;
    overflow-y: auto;
    overflow-x: hidden;
  }

  .notification-item {
    padding: 0.75rem 1rem;
    display: block;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  .notification-content {
    min-width: 0;
    max-width: 100%;
    overflow: hidden;
  }

  .notification-title {
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1.3;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
  }

  .notification-message {
    font-size: 0.75rem;
    line-height: 1.4;
    color: #6c757d;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  /* Hover effect untuk dropdown items */
  .dropdown-item:hover {
    background-color: rgba(0, 0, 0, 0.04);
  }

  .notification-item:hover {
    background-color: rgba(0, 0, 0, 0.06) !important;
  }

  /* Animation untuk unread notifications */
  @keyframes pulse {

    0%,
    100% {
      opacity: 1;
    }

    50% {
      opacity: 0.7;
    }
  }

  .bg-label-primary:not(.bg-light) {
    animation: pulse 2s ease-in-out infinite;
  }

  /* Custom scrollbar untuk notification */
  .notification-scroll::-webkit-scrollbar {
    width: 6px;
  }

  .notification-scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  .notification-scroll::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
  }

  .notification-scroll::-webkit-scrollbar-thumb:hover {
    background: #555;
  }

  /* Responsive time display */
  @media (max-width: 768px) {
    #current-time {
      font-size: 0.75rem;
    }

    .notification-dropdown {
      width: 320px !important;
    }
  }

  @media (max-width: 576px) {
    .notification-dropdown {
      width: 90vw !important;
    }
  }
</style>