@extends('layouts.app')

@section('title', 'Kelola Akun OB - Simakin')
@section('page-title', 'Kelola Akun OB')

@section('content')
<!-- Header Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text bg-white">
                <i class="bx bx-search"></i>
            </span>
            <input type="text" 
                   class="form-control" 
                   id="searchUser" 
                   placeholder="Cari akun OB..."
                   autocomplete="off">
        </div>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-info btn-lg" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bx bx-plus-circle me-2"></i> Tambah Akun OB
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-info shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Total Akun OB</small>
                        <h3 class="mb-0 text-info" id="totalUsers">{{ $users->count() }}</h3>
                    </div>
                    <div class="icon-box bg-info bg-opacity-10 rounded-circle p-3">
                        <i class="bx bx-user text-white fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Akun Aktif</small>
                        <h3 class="mb-0 text-success" id="activeUsers">{{ $users->count() }}</h3>
                    </div>
                    <div class="icon-box bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="bx bx-check-circle text-white fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Ditampilkan</small>
                        <h3 class="mb-0 text-primary" id="visibleUsers">{{ $users->count() }}</h3>
                    </div>
                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="bx bx-show text-white fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Cards Grid -->
<div class="row g-3" id="userCardsContainer">
    @forelse($users as $user)
    <div class="col-md-6 col-lg-4 col-xl-3 user-card-wrapper" 
         data-user-name="{{ strtolower($user->name) }}"
         data-user-email="{{ strtolower($user->email) }}">
        <div class="card user-card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="user-icon">
                        <i class="bx bx-user-circle text-info" style="font-size: 2.5rem;"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                                    <i class="bx bx-edit text-warning me-2"></i> Edit
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                    <i class="bx bx-trash me-2"></i> Hapus
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <h5 class="card-title mb-2">{{ $user->name }}</h5>
                
                <p class="card-text text-muted small mb-2">
                    <i class="bx bx-envelope me-1"></i>
                    {{ $user->email }}
                </p>
                
                <p class="card-text text-muted small mb-3">
                    <i class="bx bx-calendar me-1"></i>
                    Dibuat: {{ $user->created_at->format('d M Y') }}
                </p>
                
                <div class="d-flex gap-2 mt-auto">
                    <button type="button" 
                            class="btn btn-sm btn-outline-warning flex-fill" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editModal{{ $user->id }}">
                        <i class="bx bx-edit me-1"></i> Edit
                    </button>
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger flex-fill" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal{{ $user->id }}">
                        <i class="bx bx-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning bg-opacity-10">
                        <h5 class="modal-title">
                            <i class="bx bx-edit-alt me-2"></i>
                            Edit Akun OB
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name"
                                value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email"
                                value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Baru</label>
                            <input type="password" class="form-control" name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah">
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bx bx-save me-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger bg-opacity-10">
                        <h5 class="modal-title text-danger">
                            <i class="bx bx-error-circle me-2"></i>
                            Hapus Akun OB
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus akun <strong>{{ $user->name }}</strong>?</p>
                        <div class="alert alert-warning">
                            <i class="bx bx-exclamation-triangle me-2"></i>
                            <strong>Peringatan:</strong> Akun ini tidak dapat dikembalikan setelah dihapus!
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bx bx-trash me-2"></i>
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    @endforelse
</div>

<!-- No Results Message -->
<div id="noResults" class="text-center py-5" style="display: none;">
    <i class="bx bx-search-alt text-muted" style="font-size: 4rem;"></i>
    <p class="text-muted mt-3">Tidak ada akun OB yang ditemukan</p>
</div>

<!-- Empty State -->
@if($users->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bx bx-user text-muted" style="font-size: 4rem;"></i>
        <p class="text-muted mt-3 mb-3">Belum ada akun OB terdaftar</p>
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bx bx-plus-circle me-2"></i> Tambah Akun OB Pertama
        </button>
    </div>
</div>
@endif

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-info bg-opacity-10">
                    <h5 class="modal-title">
                        <i class="bx bx-plus-circle me-2"></i>
                        Tambah Akun OB Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" placeholder="contoh@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" placeholder="Minimal 8 karakter" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_confirmation"
                            placeholder="Ulangi password" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle me-2"></i>
                        Akun OB baru akan dapat login setelah dibuat.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">
                        <i class="bx bx-save me-2"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .user-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .user-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        border-color: #0dcaf0;
    }
    
    .user-icon {
        transition: transform 0.3s ease;
    }
    
    .user-card:hover .user-icon i {
        transform: scale(1.1);
    }
    
    .input-group-text {
        border-right: 0;
    }
    
    #searchUser {
        border-left: 0;
    }
    
    #searchUser:focus {
        border-color: #ced4da;
        box-shadow: none;
    }
    
    .icon-box {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .card-body {
        display: flex;
        flex-direction: column;
    }
    
    .user-card .card-body .d-flex.gap-2 {
        margin-top: auto;
    }
    
    .dropdown-menu {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .user-card-wrapper {
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchUser').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        let visibleCount = 0;
        
        $('.user-card-wrapper').each(function() {
            const userName = $(this).data('user-name');
            const userEmail = $(this).data('user-email');
            
            if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                $(this).fadeIn(300);
                visibleCount++;
            } else {
                $(this).fadeOut(300);
            }
        });
        
        // Update visible count
        $('#visibleUsers').text(visibleCount);
        
        // Show/hide no results message
        if (visibleCount === 0) {
            $('#noResults').fadeIn(300);
        } else {
            $('#noResults').fadeOut(300);
        }
    });
    
    // Show success message if exists
    @if(session('success'))
        // You can add toast notification here
        console.log('{{ session("success") }}');
    @endif
});
</script>
@endpush