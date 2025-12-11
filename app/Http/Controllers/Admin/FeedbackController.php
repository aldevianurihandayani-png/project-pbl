<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;   // <-- tambahan, untuk id_user

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
        // PENTING: pakai kolom PK yang benar, yaitu id_feedback
        $displayQuery = Feedback::query()
            ->orderByDesc('id_feedback');

        // Filter status (baru / diproses / selesai)
        if ($request->filled('status')) {
            $displayQuery->where('status', $request->status);
        }

        // Pencarian isi feedback
        // (kalau nanti mau search nama/email user, bisa ditambah relasi ke tabel users)
        if ($request->filled('q')) {
            $search = $request->q;

            $displayQuery->where(function ($q2) use ($search) {
                $q2->where('isi', 'like', "%{$search}%");
            });
        }

        $displayFeedbacks = $displayQuery->get();

        return view('admins.feedback.index', compact('allFeedbacks', 'displayFeedbacks'));
    }

    /**
     * Simpan feedback baru dari form.
     * Disesuaikan dengan struktur tabel: id_user, isi, status, ...
     */
    public function store(Request $request)
    {
        // Di form kamu boleh tetap pakai name="message" (textarea),
        // di sini kita map ke kolom "isi"
        $request->validate([
            'message' => 'required|string',
        ]);

        Feedback::create([
            'id_user'       => Auth::id(),                // user yang sedang login
            'id_notifikasi' => $request->input('id_notifikasi'), // kalau ada, boleh null
            'isi'           => $request->message,
            'status'        => 'baru',                    // default: baru
            // 'tanggal'    -> otomatis pakai DEFAULT CURRENT_TIMESTAMP di DB
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
