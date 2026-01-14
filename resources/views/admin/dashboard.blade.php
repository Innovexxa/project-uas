@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Monitor Antrian Hari Ini</h3>
        <span class="badge bg-dark p-2">{{ now()->format('d M Y') }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No. Antrian</th>
                            <th>Nama Pasien</th>
                            <th>Dokter</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $row)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">#{{ $row->queue_number }}</td>
                            <td>{{ $row->user->name }}</td>
                            <td>{{ $row->doctor->name }}</td>
                            <td><small class="text-muted">{{ Str::limit($row->symptoms, 30) }}</small></td>
                            <td>
                                @if($row->status == 'WAITING')
                                    <span class="badge rounded-pill bg-warning text-dark">Menunggu</span>
                                @elseif($row->status == 'CALLED')
                                    <span class="badge rounded-pill bg-success">Dipanggil</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary">{{ $row->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($row->status == 'WAITING')
                                <form action="{{ route('admin.call', $row->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-primary btn-sm rounded-pill px-3">Panggil</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada pasien terdaftar hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
