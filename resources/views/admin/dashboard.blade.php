@extends('layouts.app')

@section('title', 'Admin Dashboard - Simakin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-4 mb-4">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Ruangan Dibersihkan</h6>
                        <h2 class="mb-0">{{ $roomsCleanedToday }}</h2>
                        <small class="text-muted">Hari ini</small>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-door-open" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stat-card primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Tugas Selesai</h6>
                        <h2 class="mb-0">{{ $tasksDoneToday }}</h2>
                        <small class="text-muted">Hari ini</small>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Tugas Tersisa</h6>
                        <h2 class="mb-0">{{ $tasksRemainingToday }}</h2>
                        <small class="text-muted">Hari ini</small>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-hourglass-split" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Statistik Kebersihan Hari Ini</h5>
            </div>
            <div class="card-body">
                <canvas id="cleaningChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Progress Hari Ini</h5>
            </div>
            <div class="card-body">
                @php
                    $totalTasks = $tasksDoneToday + $tasksRemainingToday;
                    $percentage = $totalTasks > 0 ? round(($tasksDoneToday / $totalTasks) * 100) : 0;
                @endphp
                <div class="text-center mb-3">
                    <h1 class="display-4 mb-0">{{ $percentage }}%</h1>
                    <p class="text-muted">Tugas Selesai</p>
                </div>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-success" 
                         role="progressbar" 
                         style="width: {{ $percentage }}%"
                         aria-valuenow="{{ $percentage }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ $percentage }}%
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        {{ $tasksDoneToday }} dari {{ $totalTasks }} tugas selesai
                    </small>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body text-center">
                <i class="bi bi-calendar3 text-primary" style="font-size: 2rem;"></i>
                <h5 class="mt-3">{{ \Carbon\Carbon::now()->isoFormat('dddd') }}</h5>
                <p class="text-muted mb-0">{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.ruangan.index') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="bi bi-door-open d-block mb-2" style="font-size: 2rem;"></i>
                            Kelola Ruangan
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.tugas.index') }}" class="btn btn-outline-success btn-lg w-100">
                            <i class="bi bi-list-check d-block mb-2" style="font-size: 2rem;"></i>
                            Kelola Tugas
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info btn-lg w-100">
                            <i class="bi bi-people d-block mb-2" style="font-size: 2rem;"></i>
                            Kelola OB
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.cleaning-records.index') }}" class="btn btn-outline-warning btn-lg w-100">
                            <i class="bi bi-clipboard-data d-block mb-2" style="font-size: 2rem;"></i>
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Chart
    const ctx = document.getElementById('cleaningChart').getContext('2d');
    const cleaningChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Jumlah',
                data: {!! json_encode($chartData) !!},
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(0, 123, 255, 0.8)',
                    'rgba(255, 193, 7, 0.8)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(255, 193, 7, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush