<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LogbookController extends Controller
{
    /** Opsi enum untuk kolom minggu: "Minggu 1" .. "Minggu 18" */
    private array $mingguEnum;

    public function __construct()
    {
        $this->mingguEnum = array_map(fn ($i) => "Minggu $i", range(1, 18));
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
    public function store(Request $request)
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

        $path = $request->file('foto')
            ? $request->file('foto')->store('logbooks', 'public')
            : null;

        Logbook::create([
            'tanggal'    => $validated['tanggal'],
            'minggu'     => $validated['minggu'],
            'aktivitas'  => $validated['aktivitas'],
            'keterangan' => $validated['keterangan'] ?? null,
            'foto'       => $path,
            'user_id'    => Auth::id(),
        ]);

        return redirect()->route('logbooks.index')->with('success', 'Logbook berhasil ditambahkan.');
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
    public function update(Request $request, Logbook $logbook)
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

        $path = $logbook->foto;
        if ($request->hasFile('foto')) {
            if ($logbook->foto) {
                Storage::disk('public')->delete($logbook->foto);
            }
            $path = $request->file('foto')->store('logbooks', 'public');
        }

        $logbook->update([
            'tanggal'    => $validated['tanggal'],
            'minggu'     => $validated['minggu'],
            'aktivitas'  => $validated['aktivitas'],
            'keterangan' => $validated['keterangan'] ?? null,
            'foto'       => $path,
        ]);

        return redirect()->route('logbooks.index')->with('success', 'Logbook berhasil diperbarui.');
    }

    /** DELETE /logbooks/{logbook} */
    public function destroy(Logbook $logbook)
    {
        if (!$this->canWrite()) {
            return redirect()->route('logbooks.index')->with('error', 'Anda tidak memiliki akses.');
        }

        if ($logbook->foto) {
            Storage::disk('public')->delete($logbook->foto);
        }
        $logbook->delete();

        return redirect()->route('logbooks.index')->with('success', 'Logbook berhasil dihapus.');
    }

    /** Unduh foto/lampiran logbook (pakai kolom `foto`) */
    public function download(Logbook $logbook)
    {
        if ($logbook->foto && Storage::disk('public')->exists($logbook->foto)) {
            return Storage::disk('public')->download($logbook->foto);
        }
        return back()->with('error', 'Lampiran tidak ditemukan.');
    }

    /** Helper: cek role tulis */
    private function canWrite(): bool
    {
        return Auth::check() && in_array(Auth::user()->role, ['mahasiswa', 'admin'], true);
    }
}
