@extends('layouts.app')

@section('title', 'Kelola Ruangan - Simakin')
@section('page-title', 'Kelola Ruangan')

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
                   id="searchRoom" 
                   placeholder="Cari ruangan..."
                   autocomplete="off">
        </div>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bx bx-plus-circle me-2"></i> Tambah Ruangan
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Total Ruangan</small>
                        <h3 class="mb-0 text-primary" id="totalRooms">{{ $ruangans->count() }}</h3>
                    </div>
                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="bx bx-door-open text-white fs-1"></i>
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
                        <small class="text-muted d-block">Dengan Deskripsi</small>
                        <h3 class="mb-0 text-success" id="roomsWithDesc">{{ $ruangans->whereNotNull('description')->count() }}</h3>
                    </div>
                    <div class="icon-box bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="bx bx-check-circle text-white fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-info shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Ditampilkan</small>
                        <h3 class="mb-0 text-info" id="visibleRooms">{{ $ruangans->count() }}</h3>
                    </div>
                    <div class="icon-box bg-info bg-opacity-10 rounded-circle p-3">
                        <i class="bx bx-show text-white fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Room Cards Grid -->
<div class="row g-3" id="roomCardsContainer">
    @forelse($ruangans as $ruangan)
    <div class="col-md-6 col-lg-4 col-xl-3 room-card-wrapper" 
         data-room-name="{{ strtolower($ruangan->name) }}"
         data-room-desc="{{ strtolower($ruangan->description ?? '') }}">
        <div class="card room-card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="room-icon">
                        <i class="bx bx-door-open text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $ruangan->id }}">
                                    <i class="bx bx-edit text-warning me-2"></i> Edit
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $ruangan->id }}">
                                    <i class="bx bx-trash me-2"></i> Hapus
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <h5 class="card-title mb-2">{{ $ruangan->name }}</h5>
                
                @if($ruangan->description)
                    <p class="card-text text-muted small mb-3">
                        {{ Str::limit($ruangan->description, 80) }}
                    </p>
                @else
                    <p class="card-text text-muted small mb-3 fst-italic">
                        Tidak ada deskripsi
                    </p>
                @endif
                
                <div class="d-flex gap-2 mt-auto">
                    <button type="button" 
                            class="btn btn-sm btn-outline-warning flex-fill" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editModal{{ $ruangan->id }}">
                        <i class="bx bx-edit me-1"></i> Edit
                    </button>
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger flex-fill" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal{{ $ruangan->id }}">
                        <i class="bx bx-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal{{ $ruangan->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.ruangan.update', $ruangan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning bg-opacity-10">
                        <h5 class="modal-title">
                            <i class="bx bx-edit-alt me-2"></i>
                            Edit Ruangan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Ruangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name"
                                value="{{ $ruangan->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control" name="description"
                                rows="3" placeholder="Deskripsi ruangan (opsional)">{{ $ruangan->description }}</textarea>
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
    <div class="modal fade" id="deleteModal{{ $ruangan->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.ruangan.destroy', $ruangan->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger bg-opacity-10">
                        <h5 class="modal-title text-danger">
                            <i class="bx bx-error-circle me-2"></i>
                            Hapus Ruangan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus ruangan <strong>{{ $ruangan->name }}</strong>?</p>
                        <div class="alert alert-warning">
                            <i class="bx bx-error-circle me-2"></i>
                            <strong>Peringatan:</strong> Semua data cleaning record terkait ruangan ini akan ikut terhapus!
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
    <p class="text-muted mt-3">Tidak ada ruangan yang ditemukan</p>
</div>

<!-- Empty State -->
@if($ruangans->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bx bx-package text-muted" style="font-size: 4rem;"></i>
        <p class="text-muted mt-3 mb-3">Belum ada data ruangan</p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bx bx-plus-circle me-2"></i> Tambah Ruangan Pertama
        </button>
    </div>
</div>
@endif

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.ruangan.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary bg-opacity-10">
                    <h5 class="modal-title">
                        <i class="bx bx-plus-circle me-2"></i>
                        Tambah Ruangan Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Ruangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Contoh: Kelas 1A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3"
                            placeholder="Deskripsi ruangan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
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
    .room-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .room-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        border-color: #0d6efd;
    }
    
    .room-icon {
        transition: transform 0.3s ease;
    }
    
    .room-card:hover .room-icon i {
        transform: scale(1.1);
    }
    
    .input-group-text {
        border-right: 0;
    }
    
    #searchRoom {
        border-left: 0;
    }
    
    #searchRoom:focus {
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
    
    .room-card .card-body .d-flex.gap-2 {
        margin-top: auto;
    }
    
    .dropdown-menu {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .room-card-wrapper {
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
    $('#searchRoom').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        let visibleCount = 0;
        
        $('.room-card-wrapper').each(function() {
            const roomName = $(this).data('room-name');
            const roomDesc = $(this).data('room-desc');
            
            if (roomName.includes(searchTerm) || roomDesc.includes(searchTerm)) {
                $(this).fadeIn(300);
                visibleCount++;
            } else {
                $(this).fadeOut(300);
            }
        });
        
        // Update visible count
        $('#visibleRooms').text(visibleCount);
        
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