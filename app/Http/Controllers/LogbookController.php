<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\LogbookSubmittedMail;
use App\Services\GoogleDriveService;

class LogbookController extends Controller
{
    private array $mingguEnum;

    public function __construct()
    {
        $this->mingguEnum = array_map(fn ($i) => "Minggu $i", range(1, 18));
    }

    /**
     * ============================
     * ADMIN / GLOBAL LOGBOOK
     * ============================
     */
    public function index(Request $request)
    {
        $q = Logbook::query();

        if ($request->filled('keyword')) {
            $kw = '%' . $request->keyword . '%';
            $q->where(function ($x) use ($kw) {
                $x->where('aktivitas', 'like', $kw)
                  ->orWhere('keterangan', 'like', $kw);
            });
        }

        if ($request->filled('dari')) {
            $q->whereDate('tanggal', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $q->whereDate('tanggal', '<=', $request->sampai);
        }

        $logbooks = $q->orderByDesc('tanggal')
                      ->paginate(10)
                      ->withQueryString();

        return view('logbooks.index', compact('logbooks'));
    }

    /**
     * ============================
     * MAHASISWA LOGBOOK
     * ============================
     * URL: /mahasiswa/logbook
     */
    public function mahasiswaIndex(Request $request)
    {
        $q = Logbook::where('user_id', Auth::id());

        if ($request->filled('keyword')) {
            $kw = '%' . $request->keyword . '%';
            $q->where(function ($x) use ($kw) {
                $x->where('aktivitas', 'like', $kw)
                  ->orWhere('keterangan', 'like', $kw);
            });
        }

        if ($request->filled('dari')) {
            $q->whereDate('tanggal', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $q->whereDate('tanggal', '<=', $request->sampai);
        }

        $logbooks = $q->orderByDesc('tanggal')->get();

        // 🔥 PAKAI VIEW YANG SUDAH ADA
        return view('logbooks.index', compact('logbooks'));
    }

    /**
     * ============================
     * CREATE
     * ============================
     */
    public function create()
    {
        if (!$this->canWrite()) {
            abort(403);
        }

        $mingguOptions = $this->mingguEnum;
        return view('logbooks.create', compact('mingguOptions'));
    }

    /**
     * ============================
     * STORE
     * ============================
     */
    public function store(Request $request, GoogleDriveService $gdrive)
    {
        if (!$this->canWrite()) {
            abort(403);
        }

        $validated = $request->validate([
            'tanggal'    => ['required', 'date'],
            'minggu'     => ['required', Rule::in($this->mingguEnum)],
            'aktivitas'  => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'foto'       => ['nullable', 'image', 'max:2048'],
        ]);

        $fotoLink = null;
        if ($request->hasFile('foto')) {
            $fotoLink = $gdrive->uploadLogbookFile(
                Auth::user(),
                $request->file('foto')
            );
        }

        $logbook = Logbook::create([
            'tanggal'    => $validated['tanggal'],
            'minggu'     => $validated['minggu'],
            'aktivitas'  => $validated['aktivitas'],
            'keterangan' => $validated['keterangan'] ?? null,
            'foto'       => $fotoLink,
            'user_id'    => Auth::id(),
        ]);

        try {
            Mail::to(Auth::user()->email)
                ->send(new LogbookSubmittedMail(Auth::user(), $logbook));
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
        }

        // 🔥 BALIK KE HALAMAN MAHASISWA
        return redirect()
            ->route('mahasiswa.logbook')
            ->with('success', 'Logbook berhasil ditambahkan.');
    }

    /**
     * ============================
     * SHOW
     * ============================
     */
    public function show(Logbook $logbook)
    {
        $feedback = Feedback::where('id_notifikasi', $logbook->id)
            ->orderByDesc('tanggal')
            ->get();

        return view('logbooks.show', compact('logbook', 'feedback'));
    }

    /**
     * ============================
     * EDIT
     * ============================
     */
    public function edit(Logbook $logbook)
    {
        if (!$this->canWrite()) {
            abort(403);
        }

        $mingguOptions = $this->mingguEnum;
        return view('logbooks.edit', compact('logbook', 'mingguOptions'));
    }

    /**
     * ============================
     * UPDATE
     * ============================
     */
    public function update(Request $request, Logbook $logbook, GoogleDriveService $gdrive)
    {
        if (!$this->canWrite()) {
            abort(403);
        }

        $validated = $request->validate([
            'tanggal'    => ['required', 'date'],
            'minggu'     => ['required', Rule::in($this->mingguEnum)],
            'aktivitas'  => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'foto'       => ['nullable', 'image', 'max:2048'],
        ]);

        $fotoLink = $logbook->foto;
        if ($request->hasFile('foto')) {
            $fotoLink = $gdrive->uploadLogbookFile(
                Auth::user(),
                $request->file('foto')
            );
        }

        $logbook->update([
            'tanggal'    => $validated['tanggal'],
            'minggu'     => $validated['minggu'],
            'aktivitas'  => $validated['aktivitas'],
            'keterangan' => $validated['keterangan'] ?? null,
            'foto'       => $fotoLink,
        ]);

        return redirect()
            ->route('mahasiswa.logbook')
            ->with('success', 'Logbook berhasil diperbarui.');
    }

    /**
     * ============================
     * DELETE
     * ============================
     */
    public function destroy(Logbook $logbook)
    {
        if (!$this->canWrite()) {
            abort(403);
        }

        $logbook->delete();

        return redirect()
            ->route('mahasiswa.logbook')
            ->with('success', 'Logbook berhasil dihapus.');
    }

    /**
     * ============================
     * FEEDBACK
     * ============================
     */
    public function storeFeedback(Request $request, Logbook $logbook)
    {
        $data = $request->validate([
            'isi' => ['required', 'string'],
        ]);

        Feedback::create([
            'id_user'       => Auth::id(),
            'id_notifikasi' => $logbook->id,
            'isi'           => $data['isi'],
            'status'        => 'baru',
            'tanggal'       => now(),
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }

    /**
     * ============================
     * HELPER
     * ============================
     */
    private function canWrite(): bool
    {
        return Auth::check()
            && in_array(Auth::user()->role, ['mahasiswa', 'admin'], true);
    }
}
