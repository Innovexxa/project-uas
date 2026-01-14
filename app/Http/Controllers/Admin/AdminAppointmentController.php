<?php

namespace App\Http\Controllers\Admin; // 1. Pastikan ada \Admin

use App\Http\Controllers\Controller; // 2. Harus di-import
use App\Models\Appointment;           // 3. Harus di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['user', 'doctor'])
            ->whereDate('appointment_date', today())
            ->orderBy('queue_number', 'asc')
            ->get();

        return view('admin.appointments.index', compact('appointments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $request->status]);

        $msg = "Antrian #" . $appointment->queue_number . " berhasil diubah ke " . $request->status;
        return back()->with('success', $msg);
    }
    public function callNext($doctorId)
    {
        return DB::transaction(function () use ($doctorId) {
            // Logika pemanggilan...
            Appointment::where('doctor_id', $doctorId)
                ->where('appointment_date', now()->toDateString())
                ->where('status', 'CALLED')
                ->update(['status' => 'DONE']);

            $next = Appointment::where('doctor_id', $doctorId)
                ->where('appointment_date', now()->toDateString())
                ->where('status', 'WAITING')
                ->orderBy('queue_number', 'asc')
                ->first();

            if ($next) {
                $next->update(['status' => 'CALLED']);
                return back()->with('success', "Nomor antrian {$next->queue_number} dipanggil.");
            }
            return back()->with('error', 'Tidak ada antrian.');
        });
    }
}
