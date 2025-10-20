<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule; // <-- penting untuk Rule::in

class LogbookController extends Controller
{
    // daftar nilai enum yang valid untuk kolom `minggu`
    private array $mingguEnum = [];

    public function __construct()
    {
        // hasil: ["Minggu 1","Minggu 2",...,"Minggu 18"]
        $this->mingguEnum = array_map(fn ($i) => "Minggu $i", range(1, 18));
    }

    public function index()
    {
        $logbooks = Logbook::latest('tanggal')->get();
        return view('logbooks.index', compact('logbooks'));
    }

    public function mahasiswaIndex()
    {
        $items = Logbook::where('user_id', Auth::id())->latest('tanggal')->paginate(10);
        return view('mahasiswa.logbook', compact('items'));
    }

    public function create()
    {
        if (Auth::check() && (Auth::user()->role === 'mahasiswa' || Auth::user()->role === 'admin')) {
            $mingguOptions = $this->mingguEnum; // kirim ke view untuk dropdown
            return view('logbooks.create', compact('mingguOptions'));
        }
        return redirect()->route('logbooks.index')->with('error', 'Anda tidak memiliki akses.');
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['mahasiswa','admin'])) {
            return redirect()->route('logbooks.index')->with('error', 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'minggu'     => ['required', Rule::in($this->mingguEnum)], // enum
            'aktivitas'  => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'foto'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = $request->file('foto') ? $request->file('foto')->store('logbooks', 'public') : null;

        Logbook::create([
            'tanggal'    => $validated['tanggal'],
            'minggu'     => $validated['minggu'],   // simpan enum (e.g. "Minggu 5")
            'aktivitas'  => $validated['aktivitas'],
            'keterangan' => $validated['keterangan'] ?? null,
            'foto'       => $path,
            'user_id'    => Auth::id(),
        ]);

        return redirect()->route('logbooks.index')->with('success', 'Logbook berhasil ditambahkan.');
    }

    public function show(Logbook $logbook)
    {
        return view('logbooks.show', compact('logbook'));
    }

    public function edit(Logbook $logbook)
    {
        if (Auth::check() && (Auth::user()->role === 'mahasiswa' || Auth::user()->role === 'admin')) {
            $mingguOptions = $this->mingguEnum;
            return view('logbooks.edit', compact('logbook', 'mingguOptions'));
        }
        return redirect()->route('logbooks.index')->with('error', 'Anda tidak memiliki akses.');
    }

    public function update(Request $request, Logbook $logbook)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['mahasiswa','admin'])) {
            return redirect()->route('logbooks.index')->with('error', 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'minggu'     => ['required', Rule::in($this->mingguEnum)], // enum
            'aktivitas'  => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'foto'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            'minggu'     => $validated['minggu'],  // enum
            'aktivitas'  => $validated['aktivitas'],
            'keterangan' => $validated['keterangan'] ?? null,
            'foto'       => $path,
        ]);

        return redirect()->route('logbooks.index')->with('success', 'Logbook berhasil diperbarui.');
    }

    public function destroy(Logbook $logbook)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['mahasiswa','admin'])) {
            return redirect()->route('logbooks.index')->with('error', 'Anda tidak memiliki akses.');
        }

        if ($logbook->foto) {
            Storage::disk('public')->delete($logbook->foto);
        }
        $logbook->delete();

        return redirect()->route('logbooks.index')->with('success', 'Logbook berhasil dihapus.');
    }
}
