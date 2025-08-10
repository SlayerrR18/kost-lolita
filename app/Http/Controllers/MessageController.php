<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $adminId = User::where('role', 'admin')->first()->id;

        if ($user->role === 'admin') {
            // For admin view
            $conversations = Message::with('user')
                ->select('user_id')
                ->distinct()
                ->get()
                ->map(function($message) use ($adminId) {
                    $user = $message->user;
                    if ($user && $user->id != $adminId) {
                        // Hitung pesan dari user ini yang belum dibaca admin
                        $unread = Message::where('user_id', $user->id)
                            ->where('admin_id', $adminId)
                            ->where('is_read', false)
                            ->count();
                        $user->unread_count = $unread;
                        return $user;
                    }
                    return null;
                })
                ->filter();

            $messages = [];
            $selectedUserId = request('user_id');
            $userId = $selectedUserId; // Add this line

            if ($selectedUserId) {
                $messages = Message::where(function($query) use ($selectedUserId) {
                    $query->where('user_id', $selectedUserId)
                          ->orWhere('admin_id', $selectedUserId);
                })->with(['user', 'admin'])
                  ->orderBy('created_at', 'asc')
                  ->get();
            }

            $totalUnread = $conversations->sum('unread_count');

            // Get all users for new chat functionality
            $users = User::where('role', 'user')->get();

            return view('admin.message.index', compact(
                'conversations', 'messages', 'selectedUserId', 'userId', 'adminId', 'totalUnread', 'users'
            ));
        } else {
            // For user view
            $messages = Message::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('admin_id', $user->id);
            })
            ->with(['user', 'admin'])
            ->orderBy('created_at', 'asc')
            ->get();

            return view('user.message.index', compact('messages', 'adminId'));
        }
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Received message request', $request->all());

            $validated = $request->validate([
                'content' => 'required|string|min:1',
                'attachment' => 'nullable|file|max:5120',
                'recipient_id' => 'required|exists:users,id'
            ], [
                'content.required' => 'Pesan tidak boleh kosong',
                'content.min' => 'Pesan tidak boleh kosong',
            ]);

            $user = Auth::user();

            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('chat-attachments', 'public');
                $validated['attachment'] = $path;
            }

            $message = Message::create([
                'user_id' => $user->id,
                'admin_id' => $validated['recipient_id'],
                'content' => $validated['content'],
                'attachment' => $validated['attachment'] ?? null,
                'is_read' => false
            ]);

            $message->load(['user', 'admin']);

            \Log::info('Message created successfully', [
                'message_id' => $message->id,
                'content' => $message->content
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => route('messages.index', ['user_id' => $request->recipient_id])
            ]);

        } catch (ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->errors()['content'][0]
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Message store failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }
    }
}
