<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// PENTING: Import model Appointment agar tidak error "Class not found"
use App\Models\Appointment;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan ini true agar user bisa melakukan request
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'symptoms' => 'required|string|min:10',
        ];
    }

public function withValidator($validator)
{
    $validator->after(function ($validator) {
        // Gunakan $this untuk mengambil input di dalam Request file
        $doctorId = $this->doctor_id;
        $date = $this->appointment_date;

        // Cek apakah user sudah terdaftar di dokter & tanggal yang sama
        $exists = \App\Models\Appointment::where('user_id', auth()->id())
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->exists();

        if ($exists) {
            $validator->errors()->add('appointment_date', 'Anda sudah terdaftar di dokter ini pada tanggal tersebut.');
        }
    });
}
}

