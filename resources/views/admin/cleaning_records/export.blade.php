<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14pt;">
                LAPORAN KEBERSIHAN - SIMAKIN
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center;">
                Periode: {{ $startDate->locale('id')->isoFormat('D MMMM Y') }} - {{ $endDate->locale('id')->isoFormat('D MMMM Y') }}
            </th>
        </tr>
        <tr><th colspan="6"></th></tr>
    </thead>
    <tbody>
        @foreach($ruangans as $ruangan)
            <tr>
                <th colspan="6" style="background-color: #0c582c; color: white; font-weight: bold;">
                    RUANGAN: {{ strtoupper($ruangan->name) }}
                </th>
            </tr>
            
            @if($ruangan->cleaningRecords->count() > 0)
                @foreach($ruangan->cleaningRecords as $record)
                    <tr>
                        <td colspan="6" style="background-color: #e9ecef; font-weight: bold;">
                            Tanggal: {{ \Carbon\Carbon::parse($record->date)->locale('id')->isoFormat('dddd, D MMMM Y') }} | 
                            Petugas: {{ $record->user->name }}
                        </td>
                    </tr>
                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                        <td>No</td>
                        <td>Tugas</td>
                        <td>Status</td>
                        <td>Dikerjakan Oleh</td>
                        <td>Waktu Selesai</td>
                        <td>Progress</td>
                    </tr>
                    
                    @php
                        $totalTasks = $record->tasks->count();
                        $completedTasks = $record->tasks->where('is_done', true)->count();
                        $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    @endphp
                    
                    @foreach($record->tasks as $task)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $task->task->name }}</td>
                            <td>{{ $task->is_done ? 'Selesai' : 'Belum Selesai' }}</td>
                            <td>{{ $task->completedBy ? $task->completedBy->name : '-' }}</td>
                            <td>{{ $task->completed_at ? $task->completed_at->locale('id')->isoFormat('HH:mm, D MMMM Y') : '-' }}</td>
                            <td>{{ $loop->first ? $percentage . '%' : '' }}</td>
                        </tr>
                    @endforeach
                    <tr><td colspan="6"></td></tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="text-align: center; font-style: italic;">
                        Tidak ada data rekaman kebersihan untuk periode ini
                    </td>
                </tr>
            @endif
            <tr><td colspan="6"></td></tr>
        @endforeach
    </tbody>
</table>