<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Asumsi ada model Contact untuk feedback
        $feedbacks = Contact::latest()->paginate(2);

        return view('admins.feedback.index', [
            'feedbacks' => $feedbacks,
        ]);
    }
}
