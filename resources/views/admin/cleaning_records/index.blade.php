@extends('layouts.app')

@section('title', 'Laporan Kebersihan - Simakin')
@section('page-title', 'Laporan Kebersihan')

@section('content')
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Filter Laporan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.cleaning-records.index') }}" method="GET" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" 
                           class="form-control" 
                           name="start_date" 
                           id="start_date"
                           value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" 
                           class="form-control" 
                           name="end_date" 
                           id="end_date"
                           value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bx bx-filter-alt me-1"></i> Filter
                        </button>
                        <button type="button" class="btn btn-success flex-fill" onclick="exportExcel()">
                            <i class="bx bx-download me-1"></i> Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@foreach($ruangans as $ruangan)
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bx bx-door-open text-primary me-2"></i>
            {{ $ruangan->name }}
        </h5>
    </div>
    <div class="card-body">
        @if($ruangan->cleaningRecords->count() > 0)
            @foreach($ruangan->cleaningRecords as $record)
            <div class="mb-4 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-1">
                            <i class="bx bx-calendar me-2"></i>
                            {{ \Carbon\Carbon::parse($record->date)->isoFormat('dddd, D MMMM Y') }}
                        </h6>
                        <small class="text-muted">
                            <i class="bx bx-user me-1"></i>
                            OB: {{ $record->user->name }}
                        </small>
                    </div>
                    <div>
                        @php
                            $totalTasks = $record->tasks->count();
                            $completedTasks = $record->tasks->where('is_done', true)->count();
                            $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                        @endphp
                        <span class="badge {{ $percentage == 100 ? 'bg-success' : ($percentage > 0 ? 'bg-warning' : 'bg-secondary') }} fs-6">
                            {{ $percentage }}% Selesai
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Tugas</th>
                                <th width="15%">Status</th>
                                <th width="20%">Dikerjakan Oleh</th>
                                <th width="20%">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($record->tasks as $task)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $task->task->name }}</td>
                                <td>
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
                                        <i class="bx bx-user-check me-1"></i>
                                        {{ $task->completedBy->name }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->completed_at)
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
            <div class="text-center py-4">
                <i class="bx bx-calendar-x text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">Tidak ada data cleaning record untuk periode ini</p>
            </div>
        @endif
    </div>
</div>
@endforeach

@if($ruangans->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bx bx-inbox text-muted" style="font-size: 4rem;"></i>
        <p class="text-muted mt-3">Belum ada data ruangan</p>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function exportExcel() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    // Buat URL dengan parameter
    const url = "{{ route('admin.cleaning-records.export') }}" + 
                "?start_date=" + startDate + 
                "&end_date=" + endDate;
    
    // Redirect ke URL export
    window.location.href = url;
}
</script>
@endpush