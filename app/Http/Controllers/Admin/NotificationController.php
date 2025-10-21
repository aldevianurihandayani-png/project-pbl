<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private function baseQuery()
    {
        return Notification::query()
            ->where(function ($q) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', Auth::id());
            });
    }

    public function index(Request $request)
    {
        $query = $this->baseQuery()->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_read', $request->status === 'read');
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('course', 'like', "%{$request->search}%");
            });
        }

        $notifications = $query->paginate(15);

        return view('admins.notifikasi.index', compact('notifications', 'request'));
    }

    public function create()
    {
        $users = User::all(); // Ambil semua user
        return view('admins.notifikasi.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:materi,tugas,info',
            'title' => 'required|string|max:160',
            'course' => 'nullable|string|max:120',
            'link_url' => 'nullable|url',
            'user_id' => 'nullable|exists:users,id',
        ]);

        Notification::create($validatedData);

        return redirect()->route('admins.notifikasi.index')->with('success', 'Notifikasi berhasil dibuat.');
    }

    public function markAllRead()
    {
        $this->baseQuery()->where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    public function markRead(Notification $notification)
    {
        if ($notification->user_id !== null && $notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $notification->update(['is_read' => true]);

        return $notification->link_url
            ? redirect($notification->link_url)
            : back()->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
    }
}
