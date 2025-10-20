<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logbook;
use App\Models\Milestone;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Ambil data dinamis
        $logbooks = Logbook::where('user_id', $user->id)->latest()->take(3)->get();
        $milestones = Milestone::orderBy('deadline')->get();

        // Data untuk dikirim ke view
        $data = [
            'nama' => $user->name,
            'jumlahLogbook' => $logbooks->count(),
            'totalMilestone' => $milestones->count(),
            'milestoneSelesai' => $milestones->where('status', 'Selesai')->count(),
            'anggotaKelompok' => 0, // Anggap 0 karena tidak ada pencarian kelompok
            'logbooks' => $logbooks,
            'milestones' => $milestones,
        ];

        return view('mahasiswa.dashboard', $data);
    }
}
