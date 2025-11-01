@extends('layouts.app')

@section('title', 'Cek Kebersihan - Simakin')
@section('page-title', 'Cek Kebersihan Ruangan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Panduan:</strong> Centang setiap tugas yang sudah dikerjakan, lalu klik tombol "Simpan Checklist" untuk menyimpan progress Anda.
        </div>
    </div>
</div>

@foreach($ruangans as $ruangan)
<div class="card mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-door-open text-primary me-2"></i>
            {{ $ruangan->name }}
        </h5>
        @if($ruangan->cleaningRecords->isNotEmpty())
            @php
                $record = $ruangan->cleaningRecords->first();
                $totalTasks = $record->tasks->count();
                $completedTasks = $record->tasks->where('is_done', true)->count();
                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
            @endphp
            <span class="badge {{ $percentage == 100 ? 'bg-success' : ($percentage > 0 ? 'bg-warning text-dark' : 'bg-secondary') }} fs-6">
                {{ $completedTasks }}/{{ $totalTasks }} Selesai ({{ $percentage }}%)
            </span>
        @endif
    </div>
    <div class="card-body">
        @if($ruangan->cleaningRecords->isNotEmpty())
            @php $record = $ruangan->cleaningRecords->first(); @endphp
            
            <form action="{{ route('ob.cleaning-records.store') }}" method="POST" class="cleaning-form">
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
            <div class="text-center py-4">
                <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-2">Belum ada data cleaning record untuk ruangan ini</p>
            </div>
        @endif
    </div>
</div>
@endforeach

@if($ruangans->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
        <p class="text-muted mt-3">Belum ada ruangan yang tersedia</p>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
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
        
        // Disable button to prevent double submission
        button.prop('disabled', true);
        button.html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');
        
        // Optional: Add timeout to re-enable button if something goes wrong
        setTimeout(function() {
            button.prop('disabled', false);
            button.html('<i class="bi bi-save me-2"></i>Simpan Checklist');
        }, 5000);
    });

    // Show confirmation message on successful save
    @if(session('success'))
        // Auto scroll to top
        window.scrollTo({top: 0, behavior: 'smooth'});
        
        // Optional: Add confetti or celebration animation
        setTimeout(function() {
            // You can add confetti.js or any celebration effect here
        }, 100);
    @endif
});
</script>
@endpush