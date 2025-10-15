<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogbookController extends Controller
{
    // List + filter sederhana
    public function index(Request $request)
    {
        $q   = Logbook::query();

        if ($request->filled('keyword')) {
            $kw = '%'.$request->keyword.'%';
            $q->where(function($x) use ($kw){
                $x->where('aktivitas','like',$kw)
                  ->orWhere('keterangan','like',$kw);
            });
        }

        if ($request->filled('dari')) $q->whereDate('tanggal','>=',$request->dari);
        if ($request->filled('sampai')) $q->whereDate('tanggal','<=',$request->sampai);

        $data = $q->orderByDesc('tanggal')->paginate(10)->withQueryString();

        return view('logbook.index', compact('data'));
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'tanggal'    => ['required','date'],
            'aktivitas'  => ['required','string','max:250'],
            'keterangan' => ['nullable','string','max:250'],
            'foto'       => ['nullable','image','max:2048'], // 2 MB
        ],[
            'foto.image' => 'File foto harus berupa gambar (jpg, png, dll).'
        ]);

        if ($v->fails()) return back()->withErrors($v)->withInput();

        $payload = $request->only(['tanggal','aktivitas','keterangan']);

        // simpan ke kolom BLOB
        if ($request->hasFile('foto')) {
            $payload['foto'] = file_get_contents($request->file('foto')->getRealPath());
        }

        Logbook::create($payload);

        return back()->with('success','Logbook berhasil ditambahkan.');
    }

    public function update(Request $request, Logbook $logbook)
    {
        $v = Validator::make($request->all(), [
            'tanggal'    => ['required','date'],
            'aktivitas'  => ['required','string','max:250'],
            'keterangan' => ['nullable','string','max:250'],
            'foto'       => ['nullable','image','max:2048'],
        ]);

        if ($v->fails()) return back()->withErrors($v)->withInput();

        $logbook->tanggal    = $request->tanggal;
        $logbook->aktivitas  = $request->aktivitas;
        $logbook->keterangan = $request->keterangan;

        if ($request->hasFile('foto')) {
            $logbook->foto = file_get_contents($request->file('foto')->getRealPath());
        }

        $logbook->save();

        return back()->with('success','Logbook berhasil diperbarui.');
    }

    public function destroy(Logbook $logbook)
    {
        $logbook->delete();
        return back()->with('success','Logbook berhasil dihapus.');
    }

    // Tampilkan blob foto sebagai gambar
    public function foto(Logbook $logbook)
    {
        if (!$logbook->foto) {
            abort(404);
        }

        // deteksi mime dari bytes (fallback ke jpeg)
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($logbook->foto) ?: 'image/jpeg';

        return response($logbook->foto, 200)->header('Content-Type', $mime);
    }
}