@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Panel Antrian Perawat</h5>
                    <span>Hari Ini: {{ date('d-m-Y') }}</span>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor</th>
                                    <th>Nama Pasien</th>
                                    <th>Dokter</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $app)
                                <tr>
                                    <td class="h4 fw-bold text-primary">#{{ $app->queue_number }}</td>
                                    <td>{{ $app->user->name }}</td>
                                    <td>{{ $app->doctor->name }}</td>
                                    <td>
                                        <span class="badge {{ $app->status == 'WAITING' ? 'bg-warning' : ($app->status == 'CALLED' ? 'bg-success' : 'bg-secondary') }}">
                                            {{ $app->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if($app->status == 'WAITING')
                                                <form action="{{ route('admin.appointments.callNext', $app->doctor_id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary">Panggil Berikutnya</button>
                                                </form>
                                            @endif

                                            @if($app->status == 'CALLED')
                                                <form action="{{ route('admin.appointments.update', $app->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="DONE">
                                                    <button type="submit" class="btn btn-sm btn-success">Selesai</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada antrian hari ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
