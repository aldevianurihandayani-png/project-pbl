<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Tampilkan daftar feedback.
     */
    public function index(Request $request)
    {
        // Untuk ringkasan di sidebar (jumlah per status)
        $allFeedbacks = Feedback::all();

        // Query utama untuk tabel (pakai urutan berdasarkan id DESC, bukan created_at)
        $displayQuery = Feedback::query()
            ->orderByDesc('id');

        // Filter status (baru / diproses / selesai)
        if ($request->filled('status')) {
            $displayQuery->where('status', $request->status);
        }

        // Filter kategori (umum / bug / fitur / lainnya)
        if ($request->filled('category')) {
            $displayQuery->where('category', $request->category);
        }

        // Pencarian nama / email / isi pesan
        if ($request->filled('q')) {
            $search = $request->q;

            $displayQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $displayFeedbacks = $displayQuery->get();

        return view('admins.feedback.index', compact('allFeedbacks', 'displayFeedbacks'));
    }

    /**
     * Simpan feedback baru dari form modal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'category' => 'required|string',
            'message'  => 'required|string',
        ]);

        Feedback::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'category' => $request->category,
            'message'  => $request->message,
            'status'   => 'baru',
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
