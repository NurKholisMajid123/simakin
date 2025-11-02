@extends('layouts.app')

@section('title', 'Laporan Kebersihan - Simakin')
@section('page-title', 'Laporan Kebersihan')

@section('content')
<!-- Filter Section -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0 text-white">
            <i class="bx bx-filter-alt me-2"></i>
            Filter Laporan
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.cleaning-records.index') }}" method="GET" id="filterForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Mulai</label>
                    <input type="date" 
                           class="form-control" 
                           name="start_date" 
                           id="start_date"
                           value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Akhir</label>
                    <input type="date" 
                           class="form-control" 
                           name="end_date" 
                           id="end_date"
                           value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bx bx-filter-alt me-1"></i> Filter
                        </button>
                        <button type="button" class="btn btn-success flex-fill" onclick="exportExcel()">
                            <i class="bx bx-download me-1"></i> Export
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Room Cards View -->
<div id="room-cards-view">
    <!-- Search and Summary Section -->
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
            <div class="d-flex gap-2 justify-content-end">
                <div class="card border-primary">
                    <div class="card-body p-2 px-3">
                        <small class="text-muted d-block">Total Ruangan</small>
                        <strong class="text-primary fs-5" id="totalRooms">{{ $ruangans->count() }}</strong>
                    </div>
                </div>
                <div class="card border-success">
                    <div class="card-body p-2 px-3">
                        <small class="text-muted d-block">Dengan Data</small>
                        <strong class="text-success fs-5" id="roomsWithData">0</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Cards Grid -->
    <div class="row g-3" id="roomCardsContainer">
        @foreach($ruangans as $ruangan)
        <div class="col-md-6 col-lg-4 col-xl-3 room-card-wrapper" 
             data-room-name="{{ strtolower($ruangan->name) }}"
             data-has-data="{{ $ruangan->cleaningRecords->count() > 0 ? 'true' : 'false' }}">
            <div class="card room-card h-100 shadow-sm" 
                 onclick="showRoomDetail({{ $ruangan->id }})"
                 style="cursor: pointer;">
                <div class="card-body text-center">
                    <div class="room-icon mb-3">
                        <i class="bx bx-door-open text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title mb-2">{{ $ruangan->name }}</h5>
                    
                    @if($ruangan->cleaningRecords->count() > 0)
                        @php
                            $totalRecords = $ruangan->cleaningRecords->count();
                            $allTasksComplete = true;
                            $totalProgress = 0;
                            
                            foreach($ruangan->cleaningRecords as $record) {
                                $totalTasks = $record->tasks->count();
                                $completedTasks = $record->tasks->where('is_done', true)->count();
                                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                $totalProgress += $percentage;
                                if ($percentage < 100) $allTasksComplete = false;
                            }
                            
                            $avgProgress = $totalRecords > 0 ? round($totalProgress / $totalRecords) : 0;
                        @endphp
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">Progress Rata-rata</small>
                                <small class="fw-bold">{{ $avgProgress }}%</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $avgProgress == 100 ? 'bg-success' : 'bg-primary' }}" 
                                     role="progressbar" 
                                     style="width: {{ $avgProgress }}%;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-around text-center">
                            <div>
                                <div class="text-primary fw-bold fs-5">{{ $totalRecords }}</div>
                                <small class="text-muted">Records</small>
                            </div>
                            <div class="vr"></div>
                            <div>
                                <div class="fw-bold fs-5 {{ $allTasksComplete ? 'text-success' : 'text-warning' }}">
                                    {{ $allTasksComplete ? '✓' : '...' }}
                                </div>
                                <small class="text-muted">Status</small>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bx bx-error-circle text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0 mt-2">
                                <small>Tidak ada data</small>
                            </p>
                        </div>
                    @endif
                </div>
                <div class="card-footer text-center bg-transparent border-top-0">
                    <small class="text-primary">
                        <i class="bx bx-chevron-right"></i> Klik untuk masuk
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="text-center py-5" style="display: none;">
        <i class="bx bx-search text-muted" style="font-size: 4rem;"></i>
        <p class="text-muted mt-3">Tidak ada ruangan yang ditemukan</p>
    </div>

    @if($ruangans->isEmpty())
    <div class="card mt-4">
        <div class="card-body text-center py-5">
            <i class="bx bx-inbox text-muted" style="font-size: 4rem;"></i>
            <p class="text-muted mt-3">Belum ada data ruangan</p>
        </div>
    </div>
    @endif
</div>

<!-- Room Detail Views (Hidden by default) -->
@foreach($ruangans as $ruangan)
<div id="room-detail-{{ $ruangan->id }}" class="room-detail-view" style="display: none;">
    <div class="mb-4">
        <button type="button" class="btn btn-outline-primary" onclick="backToRoomList()">
            <i class="bx bx-arrow-left me-2"></i> Kembali ke Daftar Ruangan
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-white">
                <i class="bx bx-door-open me-2"></i>
                {{ $ruangan->name }} - Detail Laporan
            </h4>
        </div>
        <div class="card-body">
            @if($ruangan->cleaningRecords->count() > 0)
                @foreach($ruangan->cleaningRecords as $record)
                <div class="record-item mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                        <div>
                            <h6 class="mb-1 text-primary">
                                <i class="bx bx-calendar me-2"></i>
                                {{ \Carbon\Carbon::parse($record->date)->isoFormat('dddd, D MMMM Y') }}
                            </h6>
                            <small class="text-muted">
                                <i class="bx bx-user me-1"></i>
                                OB: <strong>{{ $record->user->name }}</strong>
                            </small>
                        </div>
                        <div>
                            @php
                                $totalTasks = $record->tasks->count();
                                $completedTasks = $record->tasks->where('is_done', true)->count();
                                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                            @endphp
                            <span class="badge {{ $percentage == 100 ? 'bg-success' : ($percentage > 0 ? 'bg-warning text-dark' : 'bg-secondary') }} fs-6">
                                {{ $completedTasks }}/{{ $totalTasks }} ({{ $percentage }}%)
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tugas</th>
                                    <th width="12%" class="text-center">Status</th>
                                    <th width="18%">Dikerjakan Oleh</th>
                                    <th width="18%">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($record->tasks as $task)
                                <tr class="{{ $task->is_done ? 'table-success' : '' }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-{{ $task->is_done ? 'check-circle text-success' : 'circle text-muted' }} me-2 fs-5"></i>
                                            <strong>{{ $task->task->name }}</strong>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($task->is_done)
                                            <span class="badge bg-success">
                                                <i class="bx bx-check-circle me-1"></i> Selesai
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bx bx-time me-1"></i> Belum
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->completedBy)
                                            <i class="bx bx-user-check me-1 text-primary"></i>
                                            {{ $task->completedBy->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->completed_at)
                                            <i class="bx bx-time-five me-1 text-muted"></i>
                                            {{ $task->completed_at->format('H:i, d M Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <i class="bx bx-calendar-x text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">Tidak ada data cleaning record untuk periode ini</p>
                </div>
            @endif
        </div>
    </div>
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
    
    .record-item {
        animation: fadeIn 0.5s ease;
    }
    
    .room-detail-view {
        animation: slideIn 0.4s ease;
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
    
    .table-success {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
    
    .vr {
        opacity: 0.2;
    }
</style>
@endpush

@push('scripts')
<script>
function showRoomDetail(roomId) {
    // Hide room cards view
    $('#room-cards-view').fadeOut(300, function() {
        // Show specific room detail
        $('#room-detail-' + roomId).fadeIn(300);
    });
    
    // Scroll to top
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function backToRoomList() {
    // Hide all room details
    $('.room-detail-view').fadeOut(300, function() {
        // Show room cards view
        $('#room-cards-view').fadeIn(300);
    });
    
    // Scroll to top
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function exportExcel() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    const url = "{{ route('admin.cleaning-records.export') }}" + 
                "?start_date=" + startDate + 
                "&end_date=" + endDate;
    
    window.location.href = url;
}

$(document).ready(function() {
    // Update summary counts
    function updateSummary() {
        const totalRooms = $('.room-card-wrapper').length;
        const roomsWithData = $('.room-card-wrapper[data-has-data="true"]').length;
        
        $('#totalRooms').text(totalRooms);
        $('#roomsWithData').text(roomsWithData);
    }
    
    // Search functionality
    $('#searchRoom').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        let visibleCount = 0;
        
        $('.room-card-wrapper').each(function() {
            const roomName = $(this).data('room-name');
            
            if (roomName.includes(searchTerm)) {
                $(this).fadeIn(300);
                visibleCount++;
            } else {
                $(this).fadeOut(300);
            }
        });
        
        if (visibleCount === 0) {
            $('#noResults').fadeIn(300);
        } else {
            $('#noResults').fadeOut(300);
        }
    });
    
    // Initialize
    updateSummary();
});
</script>
@endpush