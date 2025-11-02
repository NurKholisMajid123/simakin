@extends('layouts.app')

@section('title', 'Alokasi Ruangan - Simakin')
@section('page-title', 'Alokasi Ruangan per OB')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>
                        Alokasi Ruangan 
                        @if($date !== $today)
                            <small class="text-muted">({{ \Carbon\Carbon::parse($date)->format('d M Y') }})</small>
                        @endif
                    </h5>
                    <div class="d-flex align-items-center">
                        <label class="form-label me-2 mb-0">Filter Tanggal:</label>
                        <input type="date" id="filterDate" class="form-control form-control-sm" style="width: 150px;" value="{{ $date ?? $today }}">
                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="filterByDate()">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary ms-1" onclick="resetFilter()">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="assignmentForm" action="{{ route('admin.room-assignments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="assignment_id" id="assignment_id" value="">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Pilih OB</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">-- Pilih OB --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Pilih Ruangan</label>
                            <div class="row" style="max-height: 200px; overflow-y: auto;">
                                @foreach($ruangans as $ruangan)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input room-checkbox" type="checkbox" name="room_ids[]" value="{{ $ruangan->id }}" id="room_{{ $ruangan->id }}">
                                            <label class="form-check-label" for="room_{{ $ruangan->id }}">
                                                {{ $ruangan->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="assigned_date" id="assigned_date" class="form-control" value="{{ $today }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bi bi-save me-2"></i>Simpan
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
                                </button>
                            </div>
                            <div id="editInfo" class="mt-2" style="display: none;">
                                <small class="text-info">
                                    <i class="bi bi-info-circle"></i>
                                    <span id="editInfoText"></span>
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check me-2"></i>
                        Daftar Alokasi
                        @if($date !== $today)
                            <small class="text-muted">({{ \Carbon\Carbon::parse($date)->format('d M Y') }})</small>
                        @else
                            <small class="text-muted">(Hari Ini)</small>
                        @endif
                    </h5>
                    <div class="d-flex align-items-center">
                        <label class="form-label me-2 mb-0">Filter Tanggal:</label>
                        <input type="date" id="filterDate" class="form-control form-control-sm" style="width: 150px;" value="{{ $date ?? $today }}">
                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="filterByDate()">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary ms-1" onclick="resetFilter()">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($assignments->isEmpty())
                    <div class="text-center py-4">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">
                            Belum ada alokasi untuk 
                            @if($date !== $today)
                                tanggal {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                            @else
                                hari ini
                            @endif
                        </p>
                    </div>
                @else
                    @foreach($assignments as $userId => $userAssignments)
                        <div class="mb-4 p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-primary mb-0">
                                    <i class="bi bi-person me-2"></i>
                                    {{ $userAssignments->first()->user->name }}
                                    <small class="text-muted">({{ $userAssignments->count() }} ruangan)</small>
                                </h6>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" onclick="editAssignment({{ $userId }}, '{{ $userAssignments->first()->assigned_date }}')">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                @foreach($userAssignments as $assignment)
                                    <div class="col-md-3 mb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary p-2 flex-grow-1">
                                                {{ $assignment->ruangan->name }}
                                            </span>
                                            <form action="{{ route('admin.room-assignments.destroy', $assignment->id) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus alokasi" onclick="return confirm('Hapus alokasi ruangan {{ $assignment->ruangan->name }} untuk {{ $assignment->user->name }}?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Data assignments untuk edit
const assignmentsData = @json($assignments);

function editAssignment(userId, assignedDate) {
    // Reset form dulu
    resetForm();
    
    // Set user
    document.getElementById('user_id').value = userId;
    document.getElementById('assigned_date').value = assignedDate;
    
    // Set checkboxes untuk ruangan yang dialokasikan
    if (assignmentsData[userId]) {
        assignmentsData[userId].forEach(function(assignment) {
            const checkbox = document.getElementById('room_' + assignment.room_id);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    }
    
    // Update button text dan warna
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="bi bi-pencil me-2"></i>Update Alokasi';
    submitBtn.className = 'btn btn-warning';
    
    // Add visual indicator that we're in edit mode
    const formCard = document.getElementById('assignmentForm').closest('.card');
    formCard.classList.add('border-warning');
    
    // Show edit info
    const editInfo = document.getElementById('editInfo');
    const editInfoText = document.getElementById('editInfoText');
    editInfo.style.display = 'block';
    editInfoText.textContent = 'Mode edit: Mengubah alokasi yang ada. Ruangan yang tidak dipilih akan dihapus.';
    
    // Scroll ke form
    document.getElementById('assignmentForm').scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('assignmentForm').reset();
    
    // Reset button
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="bi bi-save me-2"></i>Simpan';
    submitBtn.className = 'btn btn-primary';
    
    // Remove visual indicator
    const formCard = document.getElementById('assignmentForm').closest('.card');
    formCard.classList.remove('border-warning');
    
    // Hide edit info
    document.getElementById('editInfo').style.display = 'none';
    
    // Uncheck all room checkboxes
    document.querySelectorAll('.room-checkbox').forEach(function(checkbox) {
        checkbox.checked = false;
    });
}

function filterByDate() {
    const date = document.getElementById('filterDate').value;
    if (date) {
        window.location.href = '{{ route("admin.room-assignments.index") }}?date=' + date;
    }
}

function resetFilter() {
    window.location.href = '{{ route("admin.room-assignments.index") }}';
}

function setDefaultDate() {
    const defaultDate = document.getElementById('defaultDate').value;
    document.getElementById('assigned_date').value = defaultDate;
}

// Auto-select rooms when user is selected (for new assignment)
document.getElementById('user_id').addEventListener('change', function() {
    const userId = this.value;
    const assignedDate = document.getElementById('assigned_date').value;
    
    if (userId && assignedDate) {
        // Check if user already has assignments for this date
        if (assignmentsData[userId]) {
            const existingAssignments = assignmentsData[userId].filter(function(assignment) {
                return assignment.assigned_date === assignedDate;
            });
            
            if (existingAssignments.length > 0) {
                // Auto-fill existing assignments
                existingAssignments.forEach(function(assignment) {
                    const checkbox = document.getElementById('room_' + assignment.room_id);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
                
                // Update button to indicate update mode
                document.getElementById('submitBtn').innerHTML = '<i class="bi bi-pencil me-2"></i>Update Alokasi';
            }
        }
    }
});

// Handle date change
document.getElementById('assigned_date').addEventListener('change', function() {
    // Trigger user change event to reload assignments
    document.getElementById('user_id').dispatchEvent(new Event('change'));
});

// Handle form submission to show loading state
document.getElementById('assignmentForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menyimpan...';
    
    // Reset after 3 seconds (fallback)
    setTimeout(function() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }, 3000);
});
</script>
@endpush