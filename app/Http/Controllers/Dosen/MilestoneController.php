<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Milestone;

class MilestoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $milestones = Milestone::all();
        return view('dosen.milestone', compact('milestones'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $milestone = Milestone::findOrFail($id);
        return view('dosen.milestone.edit', compact('milestone'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'minggu' => 'required',
            'kegiatan' => 'required',
            'deadline' => 'required|date',
            'status' => 'required|in:belum,pending,selesai',
        ]);

        $milestone = Milestone::findOrFail($id);
        $milestone->update($request->all());

        return redirect()->route('dosen.milestone.index')
            ->with('success', 'Milestone updated successfully');
    }
}
