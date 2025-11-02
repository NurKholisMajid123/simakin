@extends('layouts.app')

@section('title', 'Detail Laporan Kebersihan')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Detail Laporan Kebersihan</h1>
            <p class="text-muted mb-0">{{ $cleaningRecord->ruangan->name }} - {{ $cleaningRecord->date->format('d F Y') }}</p>
        </div>
        <a href="{{ route('admin.cleaning-records.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Status Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-muted mb-2">Ruangan</h6>
                            <p class="mb-0 fw-bold">{{ $cleaningRecord->ruangan->name }}</p>
                            @if($cleaningRecord->ruangan->description)
                                <small class="text-muted">{{ $cleaningRecord->ruangan->description }}</small>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-2">Tanggal</h6>
                            <p class="mb-0 fw-bold">{{ $cleaningRecord->date->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-2">Petugas</h6>
                            <p class="mb-0 fw-bold">{{ $cleaningRecord->user->name }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted mb-2">Status Ruangan</h6>
                            @if($cleaningRecord->room_cleaned)
                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>Selesai Dibersihkan
                                </span>
                            @else
                                <span class="badge bg-warning px-3 py-2">
                                    <i class="fas fa-clock me-1"></i>Dalam Progress
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Summary -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Progress Pembersihan
                    </h5>
                    @php
                        $totalTasks = $cleaningRecord->tasks->count();
                        $completedTasks = $cleaningRecord->tasks->where('is_done', true)->count();
                        $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    @endphp
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar {{ $percentage == 100 ? 'bg-success' : 'bg-primary' }}" 
                                     role="progressbar" 
                                     style="width: {{ $percentage }}%;" 
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <strong>{{ $percentage }}%</strong>
                                </div>
                            </div>
                        </div>
                        <div class="ms-3">
                            <span class="badge bg-light text-dark px-3 py-2">
                                {{ $completedTasks }} / {{ $totalTasks }} Tugas
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-tasks me-2 text-primary"></i>Daftar Tugas Kebersihan
                    </h5>
                    
                    @if($cleaningRecord->tasks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Nama Tugas</th>
                                        <th>Deskripsi</th>
                                        <th width="150">Status</th>
                                        <th width="200">Diselesaikan Oleh</th>
                                        <th width="180">Waktu Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cleaningRecord->tasks as $index => $recordTask)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $recordTask->task->name }}</strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $recordTask->task->description ?? '-' }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($recordTask->is_done)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Selesai
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-circle me-1"></i>Belum
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($recordTask->completedBy)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                            {{ substr($recordTask->completedBy->name, 0, 1) }}
                                                        </div>
                                                        <span>{{ $recordTask->completedBy->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($recordTask->completed_at)
                                                    <small>
                                                        <i class="far fa-clock me-1"></i>
                                                        {{ \Carbon\Carbon::parse($recordTask->completed_at)->format('d/m/Y H:i') }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada tugas yang terdaftar</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Section (if needed) -->
    @if($cleaningRecord->tasks->whereNotNull('note')->count() > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-sticky-note me-2 text-warning"></i>Catatan
                    </h5>
                    @foreach($cleaningRecord->tasks->whereNotNull('note') as $recordTask)
                        <div class="alert alert-info mb-2">
                            <strong>{{ $recordTask->task->name }}:</strong> {{ $recordTask->note }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.avatar-sm {
    font-size: 14px;
    font-weight: bold;
}
</style>
@endsection