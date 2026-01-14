<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Menentukan apakah user bisa melihat detail pendaftaran.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        // User hanya bisa melihat miliknya sendiri, KECUALI dia adalah admin
        return $user->id === $appointment->user_id || $user->role === 'admin';
    }

    /**
     * Menentukan apakah user bisa membatalkan antrian.
     */
    public function cancel(User $user, Appointment $appointment): bool
    {
        // Hanya pemilik yang bisa cancel DAN status harus masih WAITING
        return $user->id === $appointment->user_id && $appointment->status === 'WAITING';
    }
}
