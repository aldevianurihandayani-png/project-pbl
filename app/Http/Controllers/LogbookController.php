<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
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
        $this->mingguEnum = array_map(fn($i) => "Minggu $i", range(1, 18));
    }

    /** GET /logbooks */
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
        if ($request->filled('dari'))   $q->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $q->whereDate('tanggal', '<=', $request->sampai);

        $logbooks = $q->orderByDesc('tanggal')->paginate(10)->withQueryString();

        return view('logbooks.index', compact('logbooks'));
    }

    /** GET /logbooks/create */
    public function create()
    {
        if (!$this->canWrite()) {
            return redirect()->route('logbooks.index')->with('error', 'Anda tidak memiliki akses.');
        }

        $mingguOptions = $this->mingguEnum;
        return view('logbooks.create', compact('mingguOptions'));
    }

    /** POST /logbooks */
    public function store(Request $request, GoogleDriveService $gdrive)
    {
        if (!$this->canWrite()) {
            return redirect()->route('logbooks.index')->with('error', 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'tanggal'    => ['required', 'date'],
            'minggu'     => ['required', Rule::in($this->mingguEnum)],
            'aktivitas'  => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'foto'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $fotoLink = null;
        if ($request->hasFile('foto')) {
            $fotoLink = $gdrive->uploadLogbookFile(Auth::user(), $request->file('foto'));
        }

        $logbook = Logbook::create([
            'tanggal'    => $validated['tanggal'],
            'minggu'     => $validated['minggu'],
            'aktivitas'  => $validated['aktivitas'],
            'keterangan' => $validated['keterangan'] ?? null,
            'foto'       => $fotoLink,
            'user_id'    => Auth::id(),
        ]);

        // kirim email
        try {
            Mail::to(Auth::user()->email)->send(new LogbookSubmittedMail(Auth::user(), $logbook));
        } catch (\Throwable $e) {
            \Log::error('MAIL ERROR: '.$e->getMessage());
            return redirect()->route('logbooks.index')
                ->with('success', 'Logbook berhasil ditambahkan, tapi email gagal: '.$e->getMessage());
        }

        return redirect()->route('logbooks.index')->with('success', 'Logbook berhasil ditambahkan & email terkirim.');
    }

    /** GET /logbooks/{logbook} */
    public function show(Logbook $logbook)
    {
        return view('logbooks.show', compact('logbook'));
    }

    /** GET /logbooks/{logbook}/edit */
    public function edit(Logbook $logbook)
    {
        if (!$this->canWrite()) {
            return redirect()->route('logbooks.index')->with('error', 'Anda tidak memiliki akses.');
        }

        $mingguOptions = $this->mingguEnum;
        return view('logbooks.edit', compact('logbook', 'mingguOptions'));
    }

    /** PUT/PATCH /logbooks/{logbook} */
    public function update(Request $request, Logbook $logbook, GoogleDriveService $gdrive)
    {
        if (!$this->canWrite()) {
            return redirect()->route('logbooks.index')->with('error', 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'tanggal'    => ['required', 'date'],
            'minggu'     => ['required', Rule::in($this->mingguEnum)],
            'aktivitas'  => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'foto'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $fotoLink = $logbook->foto;
        if ($request->hasFile('foto')) {
            $fotoLink = $gdrive->uploadLogbookFile(Auth::user(), $request->file('foto'));
        }

        $logbook->update([
            'tanggal'    => $validated['tanggal'],
            'minggu'     => $validated['minggu'],
            'aktivitas'  => $validated['aktivitas'],
            'keterangan' => $validated['keterangan'] ?? null,
            'foto'       => $fotoLink,
        ]);

        return redirect()->route('logbooks.index')->with('success', 'Logbook berhasil diperbarui.');
    }

    /** DELETE /logbooks/{logbook} */
    public function destroy(Logbook $logbook)
    {
        if (!$this->canWrite()) {
            return redirect()->route('logbooks.index')->with('error', 'Anda tidak memiliki akses.');
        }

        $logbook->delete();

        return redirect()->route('logbooks.index')->with('success', 'Logbook berhasil dihapus.');
    }

    /** Helper: cek role tulis */
    private function canWrite(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['mahasiswa', 'admin'], true);
    }
}
