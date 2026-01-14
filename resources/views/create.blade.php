@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <div class="card-body">
                <h3 class="card-title fw-bold text-primary mb-4">
                    <i class="bi bi-calendar-check-fill"></i> Daftar Antrian Baru
                </h3>
                <p class="text-muted">Silakan isi formulir di bawah untuk mendapatkan nomor antrian.</p>
                <hr>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('appointments.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Dokter & Spesialisasi</label>
                        <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                            <option value="" selected disabled>-- Pilih Dokter --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }} ({{ $doctor->poli->name }}) - {{ $doctor->schedule_day }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Kunjungan</label>
                        <input type="date" name="appointment_date"
                               class="form-control @error('appointment_date') is-invalid @enderror"
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('appointment_date') }}">
                        <div class="form-text text-info"><i class="bi bi-info-circle"></i> Kuota maksimal 20 pasien per hari.</div>
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keluhan Singkat</label>
                        <textarea name="complaint" rows="3"
                                  class="form-control @error('complaint') is-invalid @enderror"
                                  placeholder="Contoh: Demam tinggi sejak semalam (Min. 10 karakter)">{{ old('complaint') }}</textarea>
                        @error('complaint')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Daftar Antrian Sekarang <i class="bi bi-arrow-right-short"></i>
                        </button>
                        <a href="{{ route('history') }}" class="btn btn-light text-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4 p-3 bg-light border-start border-warning border-4 rounded shadow-sm">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Aturan Antrian:</h6>
            <ul class="small mb-0 text-muted">
                <li>Pendaftaran dilakukan minimal pada hari kunjungan.</li>
                <li>Anda tidak dapat mendaftar di dokter dan tanggal yang sama dua kali.</li>
                <li>Pastikan datang 15 menit sebelum waktu operasional dokter dimulai.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
