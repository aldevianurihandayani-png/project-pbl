<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class LogbookController extends Controller
{
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

        if ($request->filled('dari')) $q->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $q->whereDate('tanggal', '<=', $request->sampai);

        $items = $q->orderByDesc('tanggal')->paginate(10)->withQueryString();

        return view('mahasiswa.logbook', compact('items'));
    }

    public function create()
    {
        return view('mahasiswa.logbook_create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'     => ['required', 'date'],
            'minggu'      => ['required'],
            'aktivitas'   => ['required', 'string', 'max:250'],
            'keterangan'  => ['nullable', 'string', 'max:250'],
            'lampiran'    => ['nullable', 'file', 'max:2048'],
        ], [
            'lampiran.file' => 'File lampiran harus berupa berkas.',
            'lampiran.max'  => 'Ukuran file lampiran maksimal 2 MB.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $payload = $request->only(['tanggal', 'minggu', 'aktivitas', 'keterangan']);

        if ($request->hasFile('lampiran')) {
            $payload['lampiran_path'] = $request->file('lampiran')->store('logbook_attachments');
        } else {
            $payload['lampiran_path'] = null;
        }

        $payload['status'] = 'menunggu';

        Logbook::create($payload);

        return redirect()->route('mhs.logbook.index')->with('success', 'Logbook berhasil ditambahkan.');
    }

    public function edit(Logbook $logbook)
    {
        return view('mahasiswa.logbook_edit', compact('logbook'));
    }

    public function update(Request $request, Logbook $logbook)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'     => ['required', 'date'],
            'minggu'      => ['required'],
            'aktivitas'   => ['required', 'string', 'max:250'],
            'keterangan'  => ['nullable', 'string', 'max:250'],
            'lampiran'    => ['nullable', 'file', 'max:2048'],
        ], [
            'lampiran.file' => 'File lampiran harus berupa berkas.',
            'lampiran.max'  => 'Ukuran file lampiran maksimal 2 MB.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $logbook->tanggal    = $request->tanggal;
        $logbook->minggu     = $request->minggu;
        $logbook->aktivitas  = $request->aktivitas;
        $logbook->keterangan = $request->keterangan;

        if ($request->hasFile('lampiran')) {
            if ($logbook->lampiran_path && Storage::exists($logbook->lampiran_path)) {
                Storage::delete($logbook->lampiran_path);
            }
            $logbook->lampiran_path = $request->file('lampiran')->store('logbook_attachments');
        }

        $logbook->save();

        return redirect()->route('mhs.logbook.index')->with('success', 'Logbook berhasil diperbarui.');
    }

    public function destroy(Logbook $logbook)
    {
        if ($logbook->lampiran_path && Storage::exists($logbook->lampiran_path)) {
            Storage::delete($logbook->lampiran_path);
        }
        $logbook->delete();
        return redirect()->route('mhs.logbook.index')->with('success', 'Logbook berhasil dihapus.');
    }

    public function download(Logbook $logbook)
    {
        if ($logbook->lampiran_path && Storage::exists($logbook->lampiran_path)) {
            return Storage::download($logbook->lampiran_path);
        }

        return back()->with('error', 'Lampiran tidak ditemukan.');
    }
}
