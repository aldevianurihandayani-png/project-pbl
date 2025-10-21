<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Contact;

use App\Models\Feedback;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{

    /**
     * Display a listing of the resource.
     */
   
    public function index(Request $request)
    {
        $allFeedbacks = Feedback::latest()->get();

        $displayFeedbacks = Feedback::latest()
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();
            
        return view('admins.feedback.index', compact('allFeedbacks', 'displayFeedbacks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'category' => 'required|string',
            'message' => 'required|string',
        ]);

        Feedback::create([
            'name' => $request->name,
            'email' => $request->email,
            'category' => $request->category,
            'message' => $request->message,
            'status' => 'baru',
        ]);

        return redirect()->route('admins.feedback.index')->with('success', 'Feedback berhasil ditambahkan.');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()->route('admins.feedback.index')->with('success', 'Feedback berhasil dihapus.');
    }

    public function updateStatus(Request $request, Feedback $feedback)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai',
        ]);

        $feedback->status = $request->status;
        $feedback->save();

        return redirect()->route('admins.feedback.index')->with('success', 'Status feedback berhasil diperbarui.');

    }
}
