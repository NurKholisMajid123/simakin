@extends('layouts.app')

@section('title', 'Cek Kebersihan - Simakin')
@section('page-title', 'Cek Kebersihan Ruangan')

@section('content')
@if($ruangans->isNotEmpty())
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            <strong>Alokasi Hari Ini:</strong> Anda ditugaskan untuk {{ $ruangans->count() }} ruangan.
            <br><small class="text-muted">Klik pada card ruangan untuk melihat checklist. Centang setiap tugas yang sudah dikerjakan, lalu klik tombol "Simpan Checklist".</small>
        </div>
    </div>
</div>
@endif

<!-- Room Cards View -->
<div id="room-cards-view">
    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       id="searchRoom" 
                       placeholder="Cari ruangan..."
                       autocomplete="off">
            </div>
        </div>
        <div class="col-md-6 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary filter-btn active" data-filter="all">
                    Semua <span class="badge bg-primary ms-1" id="count-all">{{ $ruangans->count() }}</span>
                </button>
                <button type="button" class="btn btn-outline-success filter-btn" data-filter="complete">
                    Selesai <span class="badge bg-success ms-1" id="count-complete">0</span>
                </button>
                <button type="button" class="btn btn-outline-warning filter-btn" data-filter="progress">
                    Proses <span class="badge bg-warning ms-1" id="count-progress">0</span>
                </button>
                <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="not-started">
                    Belum <span class="badge bg-secondary ms-1" id="count-not-started">0</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Room Cards Grid -->
    <div class="row g-3" id="roomCardsContainer">
        @foreach($ruangans as $ruangan)
        <div class="col-md-6 col-lg-4 col-xl-3 room-card-wrapper" 
             data-room-name="{{ strtolower($ruangan->name) }}"
             data-status="{{ $ruangan->cleaningRecords->isNotEmpty() ? (function() use ($ruangan) {
                 $record = $ruangan->cleaningRecords->first();
                 $totalTasks = $record->tasks->count();
                 $completedTasks = $record->tasks->where('is_done', true)->count();
                 $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                 if ($percentage == 100) return 'complete';
                 if ($percentage > 0) return 'progress';
                 return 'not-started';
             })() : 'not-started' }}">
            <div class="card room-card h-100 shadow-sm" onclick="showRoomDetail({{ $ruangan->id }})" style="cursor: pointer;">
                <div class="card-body text-center">
                    <div class="room-icon mb-3">
                        <i class="bi bi-door-open text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title mb-2">{{ $ruangan->name }}</h5>
                    
                    @if($ruangan->cleaningRecords->isNotEmpty())
                        @php
                            $record = $ruangan->cleaningRecords->first();
                            $totalTasks = $record->tasks->count();
                            $completedTasks = $record->tasks->where('is_done', true)->count();
                            $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                        @endphp
                        
                        <div class="progress mb-2" style="height: 25px;">
                            <div class="progress-bar {{ $percentage == 100 ? 'bg-success' : 'bg-primary' }}" 
                                 role="progressbar" 
                                 style="width: {{ $percentage }}%;" 
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ $percentage }}%
                            </div>
                        </div>
                        
                        <p class="mb-0">
                            <span class="badge {{ $percentage == 100 ? 'bg-success' : ($percentage > 0 ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                {{ $completedTasks }}/{{ $totalTasks }} Selesai
                            </span>
                        </p>
                    @else
                        <p class="text-muted mb-0">
                            <small>Belum ada data</small>
                        </p>
                    @endif
                </div>
                <div class="card-footer text-center bg-transparent border-top-0">
                    <small class="text-primary">
                        <i class="bi bi-chevron-right"></i> Klik untuk masuk
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="text-center py-5" style="display: none;">
        <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
        <p class="text-muted mt-3">Tidak ada ruangan yang ditemukan</p>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Room pagination">
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>
            <div class="text-center text-muted">
                <small>Menampilkan <span id="showingStart">0</span> - <span id="showingEnd">0</span> dari <span id="totalRooms">0</span> ruangan</small>
            </div>
        </div>
    </div>

    @if($ruangans->isEmpty())
    <div class="card mt-4">
        <div class="card-body text-center py-5">
            <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
            <h5 class="text-muted mt-3">Tidak Ada Alokasi Ruangan</h5>
            <p class="text-muted">Anda belum dialokasikan ruangan untuk hari ini. Silakan hubungi admin untuk mendapatkan alokasi ruangan.</p>
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Info:</strong> Hubungi administrator untuk mendapatkan alokasi ruangan kerja hari ini.
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Room Detail Views (Hidden by default) -->
@foreach($ruangans as $ruangan)
<div id="room-detail-{{ $ruangan->id }}" class="room-detail-view" style="display: none;">
    <div class="mb-4">
        <button type="button" class="btn btn-outline-primary" onclick="backToRoomList()">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Ruangan
        </button>
    </div>

    <div class="detail-header bg-primary text-white p-4 rounded-top shadow-sm">
        <h4 class="mb-0 text-white">
            <i class="bi bi-door-open me-2"></i>
            {{ $ruangan->name }}
        </h4>
    </div>
    
    @if($ruangan->cleaningRecords->isNotEmpty())
        @php $record = $ruangan->cleaningRecords->first(); @endphp
        
        <form action="{{ route('ob.cleaning-records.store') }}" method="POST" class="cleaning-form bg-white p-4 rounded-bottom shadow-sm">
            @csrf
            <input type="hidden" name="room_id" value="{{ $ruangan->id }}">
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Tugas</th>
                            <th width="15%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allTasks as $task)
                            @php 
                                $recordTask = $record->tasks->firstWhere('task_id', $task->id);
                                $isChecked = $recordTask && $recordTask->is_done;
                            @endphp
                            <tr class="task-row {{ $isChecked ? 'table-success' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-{{ $isChecked ? 'check-circle-fill text-success' : 'circle text-muted' }} me-2 fs-5"></i>
                                        <div>
                                            <strong>{{ $task->name }}</strong>
                                            @if($task->description)
                                                <br><small class="text-muted">{{ $task->description }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input task-checkbox" 
                                               type="checkbox"
                                               name="tasks[{{ $task->id }}]" 
                                               value="1"
                                               data-record-id="{{ $record->id }}"
                                               data-task-id="{{ $task->id }}"
                                               style="width: 3em; height: 1.5em;"
                                               {{ $isChecked ? 'checked' : '' }}>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <div>
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        Terakhir diupdate: {{ $record->updated_at->diffForHumans() }}
                    </small>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save me-2"></i>
                    Simpan Checklist
                </button>
            </div>
        </form>
    @else
        <div class="bg-white p-5 rounded-bottom shadow-sm text-center">
            <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-2">Belum ada data cleaning record untuk ruangan ini</p>
        </div>
    @endif
</div>
@endforeach

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
    
    .room-card:hover .room-icon {
        transform: scale(1.1);
    }
    
    .detail-header {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .task-row {
        transition: all 0.3s ease;
    }
    
    .task-row.table-success {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
    
    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .task-checkbox {
        cursor: pointer;
    }
    
    .cleaning-form {
        animation: fadeIn 0.5s ease;
    }
    
    .room-detail-view {
        animation: slideIn 0.4s ease;
    }
    
    .filter-btn.active {
        background-color: var(--bs-primary);
        color: white;
    }
    
    .filter-btn.active.btn-outline-success {
        background-color: var(--bs-success);
    }
    
    .filter-btn.active.btn-outline-warning {
        background-color: var(--bs-warning);
    }
    
    .filter-btn.active.btn-outline-secondary {
        background-color: var(--bs-secondary);
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .progress {
        border-radius: 10px;
    }
    
    .progress-bar {
        font-weight: bold;
        font-size: 14px;
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
</style>
@endpush

@push('scripts')
<script>
// Pagination variables
let currentPage = 1;
const itemsPerPage = 12;
let filteredRooms = [];
let currentFilter = 'all';

function showRoomDetail(roomId) {
    $('#room-cards-view').fadeOut(300, function() {
        $('#room-detail-' + roomId).fadeIn(300);
    });
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function backToRoomList() {
    $('.room-detail-view').fadeOut(300, function() {
        $('#room-cards-view').fadeIn(300);
    });
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function updateFilterCounts() {
    const allRooms = $('.room-card-wrapper');
    const complete = allRooms.filter('[data-status="complete"]').length;
    const progress = allRooms.filter('[data-status="progress"]').length;
    const notStarted = allRooms.filter('[data-status="not-started"]').length;
    
    $('#count-all').text(allRooms.length);
    $('#count-complete').text(complete);
    $('#count-progress').text(progress);
    $('#count-not-started').text(notStarted);
}

function filterAndSearchRooms() {
    const searchTerm = $('#searchRoom').val().toLowerCase();
    const allRooms = $('.room-card-wrapper');
    
    filteredRooms = allRooms.filter(function() {
        const roomName = $(this).data('room-name');
        const roomStatus = $(this).data('status');
        
        const matchesSearch = roomName.includes(searchTerm);
        const matchesFilter = currentFilter === 'all' || roomStatus === currentFilter;
        
        return matchesSearch && matchesFilter;
    });
    
    currentPage = 1;
    displayPage();
}

function displayPage() {
    const allRooms = $('.room-card-wrapper');
    allRooms.hide();
    
    if (filteredRooms.length === 0) {
        $('#noResults').show();
        $('#pagination').empty();
        updatePaginationInfo(0, 0, 0);
        return;
    }
    
    $('#noResults').hide();
    
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const roomsToShow = filteredRooms.slice(start, end);
    
    roomsToShow.each(function() {
        $(this).fadeIn(300);
    });
    
    updatePagination();
    updatePaginationInfo(start + 1, Math.min(end, filteredRooms.length), filteredRooms.length);
}

function updatePagination() {
    const totalPages = Math.ceil(filteredRooms.length / itemsPerPage);
    const pagination = $('#pagination');
    pagination.empty();
    
    if (totalPages <= 1) return;
    
    // Previous button
    pagination.append(`
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>
    `);
    
    // Page numbers
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(totalPages, currentPage + 2);
    
    if (startPage > 1) {
        pagination.append(`<li class="page-item"><a class="page-link" href="#" onclick="changePage(1); return false;">1</a></li>`);
        if (startPage > 2) {
            pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        pagination.append(`
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
            </li>
        `);
    }
    
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
        }
        pagination.append(`<li class="page-item"><a class="page-link" href="#" onclick="changePage(${totalPages}); return false;">${totalPages}</a></li>`);
    }
    
    // Next button
    pagination.append(`
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    `);
}

function changePage(page) {
    const totalPages = Math.ceil(filteredRooms.length / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    
    currentPage = page;
    displayPage();
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function updatePaginationInfo(start, end, total) {
    $('#showingStart').text(start);
    $('#showingEnd').text(end);
    $('#totalRooms').text(total);
}

$(document).ready(function() {
    // Initialize
    updateFilterCounts();
    filteredRooms = $('.room-card-wrapper');
    displayPage();
    
    // Search functionality
    $('#searchRoom').on('keyup', function() {
        filterAndSearchRooms();
    });
    
    // Filter functionality
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        filterAndSearchRooms();
    });
    
    // Handle checkbox change animation
    $('.task-checkbox').on('change', function() {
        const row = $(this).closest('.task-row');
        const icon = row.find('i.bi');
        
        if ($(this).is(':checked')) {
            row.addClass('table-success');
            icon.removeClass('bi-circle text-muted').addClass('bi-check-circle-fill text-success');
        } else {
            row.removeClass('table-success');
            icon.removeClass('bi-check-circle-fill text-success').addClass('bi-circle text-muted');
        }
    });

    // Handle form submission
    $('.cleaning-form').on('submit', function(e) {
        const form = $(this);
        const button = form.find('button[type="submit"]');
        
        button.prop('disabled', true);
        button.html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');
        
        setTimeout(function() {
            button.prop('disabled', false);
            button.html('<i class="bi bi-save me-2"></i>Simpan Checklist');
        }, 5000);
    });

    @if(session('success'))
        window.scrollTo({top: 0, behavior: 'smooth'});
    @endif
});
</script>
@endpush