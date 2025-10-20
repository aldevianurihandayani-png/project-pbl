<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kelompok::query();

        if ($request->has('semester') && $request->semester != '') {
            $kelasFilter = 'TI-' . $request->semester;
            if ($request->has('kelas') && $request->kelas != '') {
                $kelasFilter .= $request->kelas;
            }
            $query->where('kelas', 'like', $kelasFilter . '%');
        }

        $kelompoks = $query->get();

        return view('dosen.kelompok', [
            'kelompoks' => $kelompoks,
            'request' => $request
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dosen.kelompok.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'judul_proyek' => 'required',
            'nama_klien' => 'required',
            'ketua_kelompok' => 'required',
            'kelas' => 'required',
            'anggota' => 'required',
            'dosen_pembimbing' => 'nullable|string',
        ]);

        // Ensure 'kelas' is prefixed with 'TI-' for consistency with filtering
        if (!str_starts_with($validatedData['kelas'], 'TI-')) {
            $validatedData['kelas'] = 'TI-' . $validatedData['kelas'];
        }

        $validatedData['judul'] = $validatedData['judul_proyek'];

        Kelompok::create($validatedData);

        return redirect()->route('dosen.kelompok.index')
                        ->with('success','Kelompok created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelompok $kelompok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelompok $kelompok)
    {
        return view('dosen.kelompok.edit',compact('kelompok'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelompok $kelompok)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'judul_proyek' => 'required',
            'nama_klien' => 'required',
            'ketua_kelompok' => 'required',
            'kelas' => 'required',
            'anggota' => 'required',
            'dosen_pembimbing' => 'nullable|string',
        ]);

        // Ensure 'kelas' is prefixed with 'TI-' for consistency with filtering
        if (!str_starts_with($validatedData['kelas'], 'TI-')) {
            $validatedData['kelas'] = 'TI-' . $validatedData['kelas'];
        }

        $validatedData['judul'] = $validatedData['judul_proyek'];

        $kelompok->update($validatedData);

        return redirect()->route('dosen.kelompok.index')
                        ->with('success','Kelompok updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelompok $kelompok)
    {
        $kelompok->delete();

        return redirect()->route('dosen.kelompok.index')
                        ->with('success','Kelompok deleted successfully');
    }
}
