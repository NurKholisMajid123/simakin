@extends('layouts.app')

@section('title', 'Dashboard OB - Simakin')
@section('page-title', 'Dashboard OB')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <h3 class="mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                <p class="mb-0">Hari ini adalah {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-3 mb-4">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Pekerjaan Selesai</h6>
                        <h2 class="mb-0">{{ $pekerjaanSelesai }}</h2>
                        <small class="text-muted">Hari ini</small>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Ruangan Bersih</h6>
                        <h2 class="mb-0">{{ $ruanganBersih }}</h2>
                        <small class="text-muted">Hari ini</small>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-door-open-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Tugas Tersisa</h6>
                        <h2 class="mb-0">{{ $tugasTersisa }}</h2>
                        <small class="text-muted">Hari ini</small>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-hourglass-split" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Tugas</h6>
                        <h2 class="mb-0">{{ $totalTugas }}</h2>
                        <small class="text-muted">Jenis tugas</small>
                    </div>
                    <div class="text-danger">
                        <i class="bi bi-list-check" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Section -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Progress Pekerjaan Hari Ini</h5>
            </div>
            <div class="card-body">
                @php
                    $totalMyTasks = $pekerjaanSelesai + $tugasTersisa;
                    $myPercentage = $totalMyTasks > 0 ? round(($pekerjaanSelesai / $totalMyTasks) * 100) : 0;
                @endphp
                <div class="text-center mb-4">
                    <h1 class="display-3 mb-0 {{ $myPercentage == 100 ? 'text-success' : 'text-primary' }}">
                        {{ $myPercentage }}%
                    </h1>
                    <p class="text-muted">Tugas Selesai</p>
                </div>
                
                <div class="progress mb-3" style="height: 30px;">
                    <div class="progress-bar {{ $myPercentage == 100 ? 'bg-success' : 'bg-primary' }}" 
                         role="progressbar" 
                         style="width: {{ $myPercentage }}%"
                         aria-valuenow="{{ $myPercentage }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        <strong>{{ $myPercentage }}%</strong>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <span>
                        <i class="bi bi-check-circle text-success me-2"></i>
                        {{ $pekerjaanSelesai }} Selesai
                    </span>
                    <span>
                        <i class="bi bi-clock text-warning me-2"></i>
                        {{ $tugasTersisa }} Tersisa
                    </span>
                </div>

                @if($myPercentage == 100)
                <div class="alert alert-success mt-3 mb-0">
                    <i class="bi bi-trophy-fill me-2"></i>
                    <strong>Luar biasa!</strong> Semua tugas hari ini sudah selesai!
                </div>
                @elseif($myPercentage > 0)
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Tetap semangat! Masih ada {{ $tugasTersisa }} tugas yang perlu diselesaikan.
                </div>
                @else
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Ayo mulai bekerja! Belum ada tugas yang diselesaikan hari ini.
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Tips Kebersihan</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <i class="bi bi-1-circle-fill text-primary me-2"></i>
                        <strong>Prioritaskan tugas penting</strong>
                        <p class="mb-0 text-muted small">Mulai dari ruangan yang paling sering digunakan</p>
                    </div>
                    <div class="list-group-item px-0">
                        <i class="bi bi-2-circle-fill text-success me-2"></i>
                        <strong>Gunakan peralatan yang tepat</strong>
                        <p class="mb-0 text-muted small">Pastikan semua alat kebersihan dalam kondisi baik</p>
                    </div>
                    <div class="list-group-item px-0">
                        <i class="bi bi-3-circle-fill text-warning me-2"></i>
                        <strong>Jaga kebersihan secara konsisten</strong>
                        <p class="mb-0 text-muted small">Kebersihan rutin lebih mudah dari pembersihan mendalam</p>
                    </div>
                    <div class="list-group-item px-0 border-0">
                        <i class="bi bi-4-circle-fill text-danger me-2"></i>
                        <strong>Laporkan masalah segera</strong>
                        <p class="mb-0 text-muted small">Beritahu admin jika ada kerusakan atau masalah</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-4">
                <h5 class="mb-3">Siap untuk memulai pekerjaan?</h5>
                <a href="{{ route('ob.cleaning-records.index') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-clipboard-check me-2"></i>
                    Mulai Cek Kebersihan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient {
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
    }
</style>
@endpush