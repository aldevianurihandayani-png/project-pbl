<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;             
use App\Mail\LogbookSubmittedMail;              
use Illuminate\Support\Carbon;                   

class LogbookController extends Controller
{
    public function index()
    {
        $logbooks = Logbook::latest()->paginate(10);
        return view('logbook.index', compact('logbooks'));
    }

    public function create()
    {
        return view('logbook.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'    => ['required','date'],
            'aktivitas'  => ['required','string','max:255'],
            'keterangan' => ['required','string'],
            'foto'       => ['nullable','image','mimes:jpg,jpeg,png','max:2048'], // 2MB
        ]);

        // kalau ada user login, simpan user_id
        if ($request->user()) {
            $data['user_id'] = $request->user()->id;
        }

        if ($request->hasFile('foto')) {
            // simpan ke storage/app/public/logbook
            $data['foto'] = $request->file('foto')->store('logbook', 'public');
        }

        $logbook = Logbook::create($data);

        // === Kirim email konfirmasi ke mahasiswa (mode log atau smtp sama saja) ===
        try {
            $emailTujuan = $request->user()?->email; 
            if ($emailTujuan) {
                Mail::to($emailTujuan)->send(new LogbookSubmittedMail(
                    namaMahasiswa: $request->user()->nama ?? $request->user()->name ?? 'Mahasiswa',
                    judul: $logbook->aktivitas,
                    tanggal: Carbon::parse($logbook->tanggal)->translatedFormat('d F Y')
                ));
            }
        } catch (\Throwable $e) {
            \Log::error('Gagal kirim email logbook: '.$e->getMessage());
            // tidak memblokir alur simpan
        }

        return redirect()->route('logbook.index')->with('success','Logbook berhasil disimpan. (Email konfirmasi telah diproses)');
    }

    public function show(Logbook $logbook)
    {
        return view('logbook.show', compact('logbook'));
    }

    public function edit(Logbook $logbook)
    {
        return view('logbook.edit', compact('logbook'));
    }

    public function update(Request $request, Logbook $logbook)
    {
        $data = $request->validate([
            'tanggal'    => ['required','date'],
            'aktivitas'  => ['required','string','max:255'],
            'keterangan' => ['required','string'],
            'foto'       => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            // hapus foto lama (jika ada)
            if ($logbook->foto) {
                Storage::disk('public')->delete($logbook->foto);
            }
            $data['foto'] = $request->file('foto')->store('logbook', 'public');
        }

        $logbook->update($data);

        return redirect()->route('logbook.index')->with('success','Logbook berhasil diperbarui.');
    }

    public function destroy(Logbook $logbook)
    {
        if ($logbook->foto) {
            Storage::disk('public')->delete($logbook->foto);
        }
        $logbook->delete();

        return redirect()->route('logbook.index')->with('success','Logbook berhasil dihapus.');
    }
}
