<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Milestone;
use App\Models\MilestoneProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MahasiswaDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Pastikan user punya relasi mahasiswa
        $mhs = $user->mahasiswa()->with(['kelompok'])->first();

        // Kalau belum ada profil mahasiswa, kembalikan pesan ramah
        if (!$mhs) {
            return view('mahasiswa.dashboard', [
                'nama'              => $user->name ?? 'Mahasiswa',
                'anggotaKelompok'   => 0,
                'jumlahLogbook'     => 0,
                'milestoneSelesai'  => 0,
                'totalMilestone'    => 0,
                'progress'          => 0,
                'nilaiAkhir'        => 0,
                'milestones'        => collect(),
                'logbooks'          => collect(),
                'komponenNilai'     => collect(),
            ])->with('warning', 'Profil mahasiswa belum terhubung. Hubungi admin.');
        }

        $kelompok = $mhs->kelompok;

        // --- KPI ---
        $anggotaKelompok = $kelompok ? $kelompok->mahasiswa()->count() : 0;

        $jumlahLogbook = $mhs->logbooks()->count();

        $totalMilestone = Milestone::count();

        $milestoneSelesai = 0;
        $progressPercent  = 0;

        if ($kelompok && $totalMilestone > 0) {
            $milestoneSelesai = MilestoneProgress::where('kelompok_id', $kelompok->id)
                ->where(function($q){
                    $q->where('status','selesai')->orWhere('status','Selesai');
                })->count();

            // Jika punya kolom progress_pct, ambil rata-rata; jika tidak, fallback: selesai/total
            $avgProgress = MilestoneProgress::where('id_kelompok', $kelompok->id)->avg('progress_pct');
            if (is_null($avgProgress)) {
                $progressPercent = (int) round(($milestoneSelesai / $totalMilestone) * 100);
            } else {
                $progressPercent = (int) round($avgProgress);
            }
        }

        // --- Daftar Milestone + progress per kelompok (3 baris terbaru berdasarkan tanggal progress) ---
        $milestones = MilestoneProgress::with('milestone')
            ->when($kelompok, fn($q)=>$q->where('kelompok_id', $kelompok->id))
            ->orderByDesc('tanggal')
            ->limit(3)
            ->get()
            ->map(function($mp){
                return (object)[
                    'tanggal'  => optional($mp->tanggal ? Carbon::parse($mp->tanggal) : null)?->translatedFormat('d M Y'),
                    'nama'     => $mp->milestone?->nama ?? 'Milestone',
                    'status'   => Str::lower($mp->status ?? 'belum'),
                    'progress' => (int) ($mp->progress_pct ?? 0),
                ];
            });

        // --- Logbook terbaru (3 baris) ---
        $logbooks = $mhs->logbooks()
            ->latest('created_at')
            ->limit(3)
            ->get(['minggu','ringkasan','reviewer','status','created_at']);

        // --- Komponen nilai (dinamis) ---
        $komponenNilai = $mhs->penilaianKomponen()->get(['komponen','bobot','skor']);
        $nilaiAkhir = (float) round($komponenNilai->sum(fn($r) => ($r->bobot/100) * $r->skor), 2);

        return view('mahasiswa.dashboard', [
            'nama'              => $user->name ?? $mhs->nama ?? 'Mahasiswa',
            'anggotaKelompok'   => $anggotaKelompok,
            'jumlahLogbook'     => $jumlahLogbook,
            'milestoneSelesai'  => $milestoneSelesai,
            'totalMilestone'    => $totalMilestone,
            'progress'          => $progressPercent,
            'nilaiAkhir'        => $nilaiAkhir,

            // Tabel/daftar:
            'milestones'        => $milestones,
            'logbooks'          => $logbooks,
            'komponenNilai'     => $komponenNilai,
        ]);
    }
}
