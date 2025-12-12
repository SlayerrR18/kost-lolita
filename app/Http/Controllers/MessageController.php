<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // === LOGIKA UNTUK ADMIN ===
        if ($user->role === 'admin') {
            // 1. Ambil daftar user yang pernah chat (Conversations)
            // Logic: Ambil user selain admin, hitung unread, dan ambil pesan terakhir
            $conversations = User::where('role', '!=', 'admin')
                ->whereHas('sentMessages', function($q) { // Hanya user yang pernah kirim/terima pesan
                     $q->orWhere('recipient_id', Auth::id());
                })
                ->orWhereHas('receivedMessages', function($q) {
                     $q->orWhere('sender_id', Auth::id());
                })
                ->get()
                ->map(function ($u) {
                    $u->unread_count = Message::where('sender_id', $u->id)
                        ->where('recipient_id', Auth::id())
                        ->where('is_read', false)
                        ->count();

                    $u->last_message = Message::where(function ($q) use ($u) {
                            $q->where('sender_id', Auth::id())->where('recipient_id', $u->id);
                        })->orWhere(function ($q) use ($u) {
                            $q->where('sender_id', $u->id)->where('recipient_id', Auth::id());
                        })->latest()->first();

                    return $u;
                })->sortByDesc(fn($u) => $u->last_message?->created_at);

            // 2. Tentukan user yang sedang dibuka chat-nya
            $selectedUserId = $request->query('user_id');
            $selectedUser = $selectedUserId ? User::find($selectedUserId) : null;
            $messages = collect();

            if ($selectedUser) {
                // Tandai pesan dari user ini sebagai terbaca
                Message::where('sender_id', $selectedUserId)
                    ->where('recipient_id', $user->id)
                    ->update(['is_read' => true]);

                // Ambil pesan
                $query = Message::where(function ($q) use ($user, $selectedUserId) {
                    $q->where('sender_id', $user->id)->where('recipient_id', $selectedUserId);
                })->orWhere(function ($q) use ($user, $selectedUserId) {
                    $q->where('sender_id', $selectedUserId)->where('recipient_id', $user->id);
                });

                // Fitur Polling (ambil pesan baru saja)
                if ($request->wantsJson() && $request->has('since_id')) {
                    $query->where('id', '>', $request->since_id);
                }

                $messages = $query->orderBy('created_at', 'asc')->get();
            }

            // Jika Request AJAX (Polling), return JSON
            if ($request->wantsJson()) {
                return response()->json(['messages' => $messages]);
            }

            // Data untuk dropdown "Pesan Baru" (Semua user non-admin)
            $users = User::where('role', '!=', 'admin')->get();

            return view('admin.messages.index', compact('conversations', 'selectedUser', 'selectedUserId', 'messages', 'users'));
        }

        // === LOGIKA UNTUK USER (TENANT) ===
        else {
            // User hanya chat dengan Admin (Ambil admin pertama)
            $admin = User::where('role', 'admin')->first();
            $adminId = $admin ? $admin->id : null;
            $messages = collect();

            if ($admin) {
                // Tandai read
                Message::where('sender_id', $admin->id)
                    ->where('recipient_id', $user->id)
                    ->update(['is_read' => true]);

                $query = Message::where(function ($q) use ($user, $admin) {
                    $q->where('sender_id', $user->id)->where('recipient_id', $admin->id);
                })->orWhere(function ($q) use ($user, $admin) {
                    $q->where('sender_id', $admin->id)->where('recipient_id', $user->id);
                });

                if ($request->wantsJson() && $request->has('since_id')) {
                    $query->where('id', '>', $request->since_id);
                }

                $messages = $query->orderBy('created_at', 'asc')->get();
            }

            if ($request->wantsJson()) {
                return response()->json(['messages' => $messages]);
            }

            return view('user.messages.index', compact('messages', 'adminId'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx',
            'recipient_id' => 'required|exists:users,id',
        ]);

        if (!$request->content && !$request->hasFile('attachment')) {
            return response()->json(['success' => false, 'message' => 'Pesan tidak boleh kosong']);
        }

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('chat-attachments', 'public');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'content' => $request->content,
            'attachment' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
