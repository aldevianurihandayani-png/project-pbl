<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Tampilkan daftar feedback.
     */
    public function index(Request $request)
    {
        // Untuk ringkasan di sidebar (jumlah per status, dll)
        $allFeedbacks = Feedback::all();

        // Query utama untuk tabel
        $displayQuery = Feedback::with('user')   // relasi ke tabel users
            ->orderByDesc('tanggal');           // urut dari tanggal terbaru

        // Filter status (baru / diproses / selesai)
        if ($request->filled('status') && $request->status !== 'semua') {
            $displayQuery->where('status', $request->status);
        }

        // Pencarian: nama user / email user / isi feedback
        if ($request->filled('q')) {
            $search = $request->q;

            $displayQuery->where(function ($q) use ($search) {
                $q->where('isi', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $displayFeedbacks = $displayQuery->get();

        return view('admins.feedback.index', compact('allFeedbacks', 'displayFeedbacks'));
    }

    /**
     * Simpan feedback baru dari form modal (versi admin).
     *
     * Di DB sekarang hanya ada:
     *  - id_user
     *  - id_notifikasi
     *  - isi
     *  - status
     *  - tanggal
     *
     * Jadi input yang benar-benar dipakai hanya "message".
     */
    public function store(Request $request)
    {
        // Di form kamu boleh tetap pakai name="message" (textarea),
        // di sini kita map ke kolom "isi"
        $request->validate([
            'message'  => 'required|string',
        ]);

        Feedback::create([
            'id_user'       => Auth::id(), // admin yang sedang login
            'id_notifikasi' => null,       // tidak terkait logbook tertentu
            'isi'           => $request->message,
            'status'        => 'baru',
            'tanggal'       => now(),
        ]);

        return redirect()
            ->route('admins.feedback.index')
            ->with('success', 'Feedback berhasil ditambahkan.');
    }

    /**
     * Hapus feedback.
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()
            ->route('admins.feedback.index')
            ->with('success', 'Feedback berhasil dihapus.');
    }

    /**
     * Ubah status feedback (baru / diproses / selesai).
     */
    public function updateStatus(Request $request, Feedback $feedback)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai',
        ]);

        $feedback->status = $request->status;
        $feedback->save();

        return redirect()
            ->route('admins.feedback.index')
            ->with('success', 'Status feedback berhasil diperbarui.');
    }
}
