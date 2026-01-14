@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">Daftar Antrian</div>
                <div class="card-body">
                    @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Pilih Dokter</label>
                            <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Dokter --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        {{ $doctor->name }} - {{ $doctor->poli->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal Kunjungan</label>
                            <input type="date" name="appointment_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Keluhan (Min 10 Karakter)</label>
                            <textarea name="symptoms" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ambil Nomor Antrian</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Riwayat Antrian Anda</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Dokter</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myAppointments as $app)
                                <tr>
                                    <td>#{{ $app->queue_number }}</td>

                                    <td>{{ $app->doctor->name }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $app->status }}</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('appointments.destroy', $app->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
