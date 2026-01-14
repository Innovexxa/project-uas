<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // Tambahkan fillable agar data bisa disimpan ke database
    protected $fillable = [
        'user_id',
        'doctor_id',
        'appointment_date',
        'symptoms',
        'queue_number',
        'status'
    ];

    /**
     * Relasi ke User (Pasien)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Dokter
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
