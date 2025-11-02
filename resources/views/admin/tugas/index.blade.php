@extends('layouts.app')

@section('title', 'Kelola Tugas - Simakin')
@section('page-title', 'Kelola Tugas')

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
                   id="searchTask" 
                   placeholder="Cari tugas..."
                   autocomplete="off">
        </div>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bx bx-plus-circle me-2"></i> Tambah Tugas
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-success shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Total Tugas</small>
                        <h3 class="mb-0 text-success" id="totalTasks">{{ $tugas->count() }}</h3>
                    </div>
                    <div class="icon-box bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="bx bx-task text-white fs-1"></i>
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
                        <small class="text-muted d-block">Dengan Deskripsi</small>
                        <h3 class="mb-0 text-primary" id="tasksWithDesc">{{ $tugas->whereNotNull('description')->count() }}</h3>
                    </div>
                    <div class="icon-box bg-primary bg-opacity-10 rounded-circle p-3">
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
                        <h3 class="mb-0 text-info" id="visibleTasks">{{ $tugas->count() }}</h3>
                    </div>
                    <div class="icon-box bg-info bg-opacity-10 rounded-circle p-3">
                        <i class="bx bx-show text-white fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Cards Grid -->
<div class="row g-3" id="taskCardsContainer">
    @forelse($tugas as $task)
    <div class="col-md-6 col-lg-4 col-xl-3 task-card-wrapper" 
         data-task-name="{{ strtolower($task->name) }}"
         data-task-desc="{{ strtolower($task->description ?? '') }}">
        <div class="card task-card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="task-icon">
                        <i class="bx bx-check-square text-success" style="font-size: 2.5rem;"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $task->id }}">
                                    <i class="bx bx-edit text-warning me-2"></i> Edit
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $task->id }}">
                                    <i class="bx bx-trash me-2"></i> Hapus
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <h5 class="card-title mb-2">{{ $task->name }}</h5>
                
                @if($task->description)
                    <p class="card-text text-muted small mb-3">
                        {{ Str::limit($task->description, 100) }}
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
                            data-bs-target="#editModal{{ $task->id }}">
                        <i class="bx bx-edit me-1"></i> Edit
                    </button>
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger flex-fill" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal{{ $task->id }}">
                        <i class="bx bx-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal{{ $task->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.tugas.update', $task->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning bg-opacity-10">
                        <h5 class="modal-title">
                            <i class="bx bx-edit-alt me-2"></i>
                            Edit Tugas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Tugas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name"
                                value="{{ $task->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control" name="description"
                                rows="3" placeholder="Deskripsi tugas (opsional)">{{ $task->description }}</textarea>
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
    <div class="modal fade" id="deleteModal{{ $task->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.tugas.destroy', $task->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger bg-opacity-10">
                        <h5 class="modal-title text-danger">
                            <i class="bx bx-error-circle me-2"></i>
                            Hapus Tugas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus tugas <strong>{{ $task->name }}</strong>?</p>
                        <div class="alert alert-warning">
                            <i class="bx bx-error-circle me-2"></i>
                            <strong>Peringatan:</strong> Tugas ini akan dihapus dari semua cleaning record!
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
    <p class="text-muted mt-3">Tidak ada tugas yang ditemukan</p>
</div>

<!-- Empty State -->
@if($tugas->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bx bx-task text-muted" style="font-size: 4rem;"></i>
        <p class="text-muted mt-3 mb-3">Belum ada data tugas</p>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bx bx-plus-circle me-2"></i> Tambah Tugas Pertama
        </button>
    </div>
</div>
@endif

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.tugas.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success bg-opacity-10">
                    <h5 class="modal-title">
                        <i class="bx bx-plus-circle me-2"></i>
                        Tambah Tugas Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Tugas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Contoh: Menyapu lantai" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3"
                            placeholder="Deskripsi tugas (opsional)"></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle me-2"></i>
                        Tugas baru akan otomatis ditambahkan ke semua cleaning record hari ini.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
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
    .task-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .task-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        border-color: #198754;
    }
    
    .task-icon {
        transition: transform 0.3s ease;
    }
    
    .task-card:hover .task-icon i {
        transform: scale(1.1);
    }
    
    .input-group-text {
        border-right: 0;
    }
    
    #searchTask {
        border-left: 0;
    }
    
    #searchTask:focus {
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
    
    .task-card .card-body .d-flex.gap-2 {
        margin-top: auto;
    }
    
    .dropdown-menu {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .task-card-wrapper {
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
    $('#searchTask').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        let visibleCount = 0;
        
        $('.task-card-wrapper').each(function() {
            const taskName = $(this).data('task-name');
            const taskDesc = $(this).data('task-desc');
            
            if (taskName.includes(searchTerm) || taskDesc.includes(searchTerm)) {
                $(this).fadeIn(300);
                visibleCount++;
            } else {
                $(this).fadeOut(300);
            }
        });
        
        // Update visible count
        $('#visibleTasks').text(visibleCount);
        
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
@endpus