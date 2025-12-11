<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosens = Dosen::all();
        return view('admins.dosen.index', compact('dosens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.dosen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_dosen' => 'required',
            'nip' => 'nullable',
            'jabatan' => 'nullable',
            'no_telp' => 'nullable',
        ]);

        Dosen::create($request->all());

        return redirect()->route('admins.dosen.index')
            ->with('success', 'Dosen created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('admins.dosen.edit', compact('dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_dosen' => 'required',
            'nip' => 'nullable',
            'jabatan' => 'nullable',
            'no_telp' => 'nullable',
        ]);

        $dosen = Dosen::findOrFail($id);
        $dosen->update($request->all());

        return redirect()->route('admins.dosen.index')
            ->with('success', 'Dosen updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return redirect()->route('admins.dosen.index')
            ->with('success', 'Dosen deleted successfully.');
    }
}
